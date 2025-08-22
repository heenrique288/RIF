<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlunoModel;
use App\Models\AlunoEmailModel;
use App\Models\AlunoTelefoneModel;
use App\Models\TurmaModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class AlunoController extends BaseController
{
    protected $baseRoute = '/sys/alunos';

    public function index()
    {
        $alunoModel = new AlunoModel();
        $alunoEmailModel = new AlunoEmailModel();
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $turmaModel = new TurmaModel();
        
        $alunos = $alunoModel
            ->select('alunos.*, t.nome as turma_nome, c.nome as curso_nome')
            ->join('turmas as t', 't.id = alunos.turma_id', 'left')
            ->join('cursos as c', 'c.id = t.curso_id', 'left')
            ->orderBy('alunos.nome')
            ->findAll();

        foreach ($alunos as &$aluno) {
            $emails = $alunoEmailModel->where('aluno_id', $aluno['matricula'])->findAll();
            $aluno['emails'] = array_column($emails, 'email');
            
            $telefones = $alunoTelefoneModel->where('aluno_id', $aluno['matricula'])->findAll();
            $aluno['telefones'] = array_column($telefones, 'telefone');
        }

        $data['alunos'] = $alunos;
        $data['turmas'] = $turmaModel
            ->select('turmas.*, c.nome as curso_nome')
            ->join('cursos as c', 'c.id = turmas.curso_id', 'left')
            ->orderBy('turmas.nome')
            ->findAll();

        $data['content'] = view('sys/aluno', $data);
        return view('dashboard', $data);
    }
    
    public function create()
    {
        $alunoModel = new AlunoModel();
        $alunoEmailModel = new AlunoEmailModel();
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $post = $this->request->getPost();

        $alunoData = [
            'matricula' => strip_tags($post['matricula']),
            'nome'      => strip_tags($post['nome']),
            'turma_id'  => (int)strip_tags($post['turma_id']),
            'status'    => strip_tags($post['status']),
        ];

        // Se a validação do aluno falhar (matrícula ou nome duplicados)
        if (!$alunoModel->validate($alunoData)) {
            return $this->redirectToBaseRoute($alunoModel->errors());
        }

        $alunoModel->db->transBegin();

        try {
            $alunoModel->insert($alunoData);
            // Coleta e insere os e-mails
            $emails = $post['email'] ?? [];
            foreach ($emails as $email) {
                $emailData = ['aluno_id' => $alunoData['matricula'], 'email' => trim($email), 'status' => 'ativo'];
                if (!$alunoEmailModel->validate($emailData) || !$alunoEmailModel->insert($emailData)) {
                    $alunoModel->db->transRollback();
                    return $this->redirectToBaseRoute($alunoEmailModel->errors() ?? ['Erro ao inserir e-mail.']);
                }
            }
            // Coleta e insere os telefones
            $telefones = $post['telefone'] ?? [];
            foreach ($telefones as $telefone) {
                $telefoneData = ['aluno_id' => $alunoData['matricula'], 'telefone' => trim(str_replace(['(', ')', ' ', '-', '+'], '', $telefone)), 'status' => 'ativo'];
                if (!$alunoTelefoneModel->validate($telefoneData) || !$alunoTelefoneModel->insert($telefoneData)) {
                    $alunoModel->db->transRollback();
                    return $this->redirectToBaseRoute($alunoTelefoneModel->errors() ?? ['Erro ao inserir telefone.']);
                }
            }
            $alunoModel->db->transCommit();
            session()->setFlashdata('sucesso', 'Aluno cadastrado com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            $alunoModel->db->transRollback();
            log_message('error', 'Erro ao criar aluno: ' . $e->getMessage());
            return $this->redirectToBaseRoute(['Ocorreu um erro inesperado. Tente novamente.']);
        }
    }

    public function update()
    {
        $alunoModel = new AlunoModel();
        $alunoEmailModel = new AlunoEmailModel();
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $post = $this->request->getPost();
        
        $matricula = strip_tags($post['matricula']);

        $alunoModel->db->transBegin();
        
        try {
            $alunoData = [
                'nome'      => strip_tags($post['nome']),
                'turma_id'  => (int)strip_tags($post['turma_id']),
                'status'    => strip_tags($post['status']),
            ];

            if (!$alunoModel->update($matricula, $alunoData)) {
                $alunoModel->db->transRollback();
                return $this->redirectToBaseRoute($alunoModel->errors());
            }

            if (!empty($post['email'])) {
                // pega todos do banco
                $emailsExistentes = $alunoEmailModel->where('aluno_id', $matricula)->findAll();
                
                // pega os id dos emails enviados do form
                $idsDeEmailsEnviados = $post['email_id'] ?? [];
                $idsDeEmailsEnviados = array_filter($idsDeEmailsEnviados);

                // deleta os do banco que não foram enviados do form
                foreach ($emailsExistentes as $emailExistente) {
                    if (!in_array($emailExistente['id'], $idsDeEmailsEnviados)) {
                        $alunoEmailModel->delete($emailExistente['id']);
                    }
                }
                
                // Salva ou seta os vieram do form
                foreach ($post['email'] as $i => $email) {
                    $emailId = $post['email_id'][$i] ?? null;

                    $emailData = [
                        'aluno_id' => $matricula,
                        'email'    => trim($email),
                        'status'   => 'ativo',
                    ];

                    if ($emailId) {
                        $emailData['id'] = $emailId;
                    }

                    $alunoEmailModel->save($emailData); 
                }
            }

            if (!empty($post['telefone'])) {
                // pega todos do banco
                $telefonesExistentes = $alunoTelefoneModel->where('aluno_id', $matricula)->findAll();
                
                // pega os id dos telefones enviados do form
                $idsDeTelefonesEnviados = $post['telefone_id'] ?? [];
                $idsDeTelefonesEnviados = array_filter($idsDeTelefonesEnviados); 

                // deleta os do banco que não foram enviados do form
                foreach ($telefonesExistentes as $telefoneExistente) {
                    if (!in_array($telefoneExistente['id'], $idsDeTelefonesEnviados)) {
                        $alunoTelefoneModel->delete($telefoneExistente['id']);
                    }
                }
                
                // Salva ou seta os vieram do form
                foreach ($post['telefone'] as $i => $telefone) {
                    $telefoneId = $post['telefone_id'][$i] ?? null;

                    $telefoneData = [
                        'aluno_id' => $matricula,
                        'telefone' => trim(str_replace(['(', ')', '-', ' '], '', $telefone)), //deixa limpo
                        'status'   => 'ativo',
                    ];

                    if ($telefoneId) {
                        $telefoneData['id'] = $telefoneId;
                    }

                    $alunoTelefoneModel->save($telefoneData);
                }
            }
            //$this->updateAssociatedData($alunoEmailModel, $post['email'] ?? [], $matricula, 'email', $alunoData['status']);
            //$this->updateAssociatedData($alunoTelefoneModel, $post['telefone'] ?? [], $matricula, 'telefone', $alunoData['status']);

            $alunoModel->db->transCommit();
            session()->setFlashdata('sucesso', 'Aluno, e-mails e telefones atualizados com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            $alunoModel->db->transRollback();
            return $this->redirectToBaseRoute(['Ocorreu um erro ao editar o aluno, e-mails e/ou telefones!']);
        }
    }
    
    public function delete()
    {
        $alunoModel = new AlunoModel();
        $alunoEmailModel = new AlunoEmailModel();
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $post = $this->request->getPost();
        
        $matricula = strip_tags($post['matricula']);

        $alunoModel->db->transBegin();
        
        try {
            $alunoTelefoneModel->where('aluno_id', $matricula)->delete();
            $alunoEmailModel->where('aluno_id', $matricula)->delete();
            $alunoModel->where('matricula', $matricula)->delete();
            
            if ($alunoModel->db->transStatus() === false) {
                $alunoModel->db->transRollback();
                return $this->redirectToBaseRoute(['Erro ao deletar o aluno.']);
            }

            $alunoModel->db->transCommit();
            session()->setFlashdata('sucesso', 'Aluno e dados relacionados deletados com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            $alunoModel->db->transRollback();
            return $this->redirectToBaseRoute(['Ocorreu um erro ao deletar o aluno!']);
        }
    }
    
    public function edit($matricula)
    {
        $alunoModel = new AlunoModel();
        $alunoEmailModel = new AlunoEmailModel();
        $alunoTelefoneModel = new AlunoTelefoneModel();
        
        $aluno = $alunoModel->find($matricula);

        if ($aluno === null) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Aluno não encontrado.']);
        }

        $aluno['status'] = ($aluno['status'] == 1) ? 'ativo' : 'inativo';
        
        $emails = $alunoEmailModel->where('aluno_id', $matricula)->findAll();
        $aluno['emails'] = array_column($emails, 'email');

        $telefones = $alunoTelefoneModel->where('aluno_id', $matricula)->findAll();
        $aluno['telefones'] = array_column($telefones, 'telefone');

        return $this->response->setJSON($aluno);
    }



    /**
     * @route POST /alunos/import
     */
    public function import()
    {
        $post = $this->request->getFiles();
        $planilha = $post['planilha-alunos']; 

        //Tratamento
        if (!$planilha->isValid()){
            session()->setFlashdata('erros', ['O arquivo é inválido. Por favor, tente novamente.']);
            return redirect()->to(base_url('sys/alunos'));
        } 
        else if(!in_array($planilha->getClientExtension(), ['xls', 'xlsx'])){
            session()->setFlashdata('erros', ['Formato de arquivo não suportado. Use .XLS ou .XLSX.']);
            return redirect()->to(base_url('sys/alunos'));
        }

        $reader = $planilha->getClientExtension() === 'xlsx' ? new Xlsx() : new Xls();
        
        try 
        {
            $spreadsheet = $reader->load($planilha->getRealPath());
        } 
        catch (\Exception $e) 
        {
            session()->setFlashdata('erros', ['Ocorreu um erro ao carregar a planilha.']);
            return redirect()->to(base_url('sys/alunos'));
        }

        $sheet = $spreadsheet->getActiveSheet(); //Pega a 1° aba
        $dataRows = [];
        $primeiraLinha = true;

        foreach ($sheet->getRowIterator() as $row) 
        {
            //Pra não contar com a primeira linha que é cabeçalho
            if ($primeiraLinha) 
            {
                $primeiraLinha = false;
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowData = [];

            foreach ($cellIterator as $cell) 
            {
                $rowData[] = $cell->getValue(); //pega os dados da linha
            }

            $matricula = $rowData[1];
            $matricula_limpa = preg_replace('/[^0-9]/', '', $matricula);

            $nome = $rowData[2];
            $email = []; //academico, pessoal, responsavel

            // coluna G 
            if (!empty($rowData[6])) {
                $email[] = $rowData[6];
            }
            // coluna I 
            if (!empty($rowData[8])) {
                $email[] = $rowData[8];
            }
            // coluna J 
            if (!empty($rowData[9])) {
                $email[] = $rowData[9];
            }
                            
            $status = $rowData[10];
            $status_padrao = (strtolower($status) === 'Matriculado') ? 'ativo' : 'inativo';

            $telefone =[];

            if (isset($rowData[12])) {
                $telefones = explode(', ', $rowData[12]); //separa eles
                foreach ($telefones as $tel) {
                    $tel = trim($tel);
                    if (!empty($tel)) {
                        $telefone[] = $tel; 
                    }
                }
            }

            $dataRows[] = [
                'matricula' => $matricula_limpa,
                'nome'      => $nome,
                'email'     => $email,
                'status'    => $status_padrao,
                'telefone' => $telefone
            ];

        }

        $data['alunos'] = $dataRows;
        $data['import_completo'] = true;
        $mainContent = view('sys/aluno-importar-form', $data);
        return view('dashboard', ['content' => $mainContent]);
        
    }

    /**
     * @route POST /alunos/importProcess
     */
    public function importProcess()
    {
        $selecionados = $this->request->getPost('selecionados');

        if (empty($selecionados)) {
            session()->setFlashdata('erros', ['Nenhum aluno foi selecionado para importação.']);
            return redirect()->to('sys/alunos');
        }

        $aluno = new AlunoModel();
        $emailModel = new AlunoEmailModel();
        $telefoneModel = new AlunoTelefoneModel();
        
        $insertedCount = 0;
        $errors = [];

        foreach ($selecionados as $alunoJson) {
            $alunoData = json_decode($alunoJson, true, 512, JSON_BIGINT_AS_STRING);

            $data = [
                'matricula' => $alunoData['matricula'],
                'nome'      => $alunoData['nome'],
                'status' => $alunoData['status'], 
            ];

            $sucesso = $aluno->insert($data);

            if (!$sucesso) {
                $errosDoModelo = $aluno->errors();
                $errors[] = "Ocorreu um erro ao importar o aluno de matrícula {$alunoData['matricula']}: " . implode(', ', $errosDoModelo);
            } else {
                $insertedCount++;
                $alunoId = $aluno->getInsertID();

                //Faz a inserção dos emails
                if (!empty($alunoData['email'])) {
                    foreach ($alunoData['email'] as $email) {
                        // se não é vazio ou apenas um hífen
                        if (!empty(trim($email)) && trim($email) !== '-') {
                            $emailModel->insert([
                                'aluno_id' => $alunoId,
                                'email'    => $email,
                                'status' => 'ativo' //deixar assim por enquanto até verificar como será validado
                            ]);
                        }
                    }
                }

                //Faz a inserção dos telefones
                if (!empty($alunoData['telefone'])) {
                    foreach ($alunoData['telefone'] as $telefone) {
                        if (!empty(trim($telefone)) && trim($telefone) !== '-') {

                            $telefonePadrao = str_replace(['(', ')', ' ', '-'], '', $telefone);
                            
                            $telefoneModel->insert([
                                'aluno_id' => $alunoId,
                                'telefone' => $telefonePadrao,
                                'status' => 'ativo' //deixar assim por enquanto até verificar como será validado
                            ]);
                        }
                    }
                }


            }
        }

        $redirect = redirect()->to('sys/alunos');

        if ($insertedCount > 0) {
            $redirect->with('sucesso', "{$insertedCount} aluno(s) importado(s) com sucesso!");
        }

        if (!empty($errors)) {
            $redirect->with('erros', $errors);
        }
        
        return $redirect;
    }

    protected function redirectToBaseRoute($errors = null)
    {
        if ($errors) {
            session()->setFlashdata('erros', $errors);
            return redirect()->to($this->baseRoute)->withInput();
        }
        
        return redirect()->to($this->baseRoute);
    }
}