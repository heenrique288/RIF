<?php

namespace App\Controllers;

use App\Models\AlunoModel;
use App\Models\TurmaModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AlunoController extends BaseController
{
    public function index()
    {
        $alunoModel = new AlunoModel();
        $turmaModel = new TurmaModel();
        
        $alunos = $alunoModel->paginate(10);
        $pager = $alunoModel->pager;

        $dataAlunos = [
            'alunos' => $alunos,
            'pager' => $pager,
            'turmas' => $turmaModel->select('turmas.*, cursos.nome as curso_nome')->join('cursos', 'cursos.id = turmas.curso_id')->findAll(),
        ];
        
        $mainContent = view('sys/aluno', $dataAlunos);

        $data = [
            'content' => $mainContent,
        ];
        
        return view('dashboard', $data);
    }
    
    public function criar()
    {
        $alunoModel = new AlunoModel();
        $postData = $this->request->getPost();
        
        // CORREÇÃO: A validação e a inserção agora são mais simples. O modelo lida com a conversão.
        if (!$alunoModel->insert($postData)) {
            return redirect()->back()->withInput()->with('errors', $alunoModel->errors());
        } else {
            return redirect()->to('sys/alunos')->with('success', 'Aluno cadastrado com sucesso!');
        }
    }
    
    public function delete($id)
    {
        $alunoModel = new AlunoModel();
        
        if ($alunoModel->delete($id)) {
            return redirect()->to('sys/alunos')->with('success', 'Aluno deletado com sucesso!');
        } else {
            return redirect()->to('sys/alunos')->with('errors', ['Erro ao deletar o aluno.']);
        }
    }
    
    public function edit($id)
    {
        $alunoModel = new AlunoModel();
        $aluno = $alunoModel->find($id);

        if ($aluno === null) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Aluno não encontrado.']);
        }

        return $this->response->setJSON($aluno);
    }

    public function update($id)
    {
        $alunoModel = new AlunoModel();
        $postData = $this->request->getPost();
        
        unset($postData['matricula']);
        unset($postData['_method']);

        if (!$alunoModel->update($id, $postData)) {
            return redirect()->back()->withInput()->with('errors', $alunoModel->errors());
        } else {
            return redirect()->to('sys/alunos')->with('success', 'Aluno atualizado com sucesso!');
        }
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
        // depois vai existir os models de telefone e email
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
            //depois vai ser feita a inserção de email e telefone

            if (!$sucesso) {
                $errosDoModelo = $aluno->errors();
                $errors[] = "Ocorreu um erro ao importar o aluno de matrícula {$alunoData['matricula']}: " . implode(', ', $errosDoModelo);
            } else {
                $insertedCount++;
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

}