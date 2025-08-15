<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TurmaModel;
use App\Models\CursoModel;
use App\Models\AlunoModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class TurmaController extends BaseController
{
    protected $baseRoute = '/sys/turmas';

    public function index()
    {
        $turmas_model = new TurmaModel();
        $cursos_model = new CursoModel();

        $turmas = $turmas_model->select('turmas.*, c.nome as curso_nome')
            ->join('cursos as c', 'c.id = turmas.curso_id', 'left')
            ->findAll();

        $data['turmas'] = $turmas;
        $data['cursos'] = $cursos_model->findAll();

        $data['content'] = view('sys/turmas', $data);
        return view('dashboard', $data);
    }

    /**
     * @route POST /turmas/create
     */
    public function create()
    {
        $post = $this->request->getPost();

        $input['nome'] = strip_tags($post['nome']);
        $input['curso_id'] = (int) strip_tags($post['curso_id']);

        try {
            $turma = new TurmaModel();
            $sucesso = $turma->insert($input);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($turma->errors());
            }

            session()->setFlashdata('sucesso', 'Turma cadastrada com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro ao cadastrar a turma!']);
        }
    }

    /**
     * @route POST /turmas/update
     */
    public function update()
    {
        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);
        $input['curso_id'] = (int) strip_tags($post['curso_id']);

        try {
            $turma = new TurmaModel();
            $sucesso = $turma->save($input);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($turma->errors());
            }

            session()->setFlashdata('sucesso', 'Turma atualizada com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro ao editar a turma!']);
        }
    }

    /**
     * @route POST /turmas/delete
     */
    public function delete()
    {
        $post = $this->request->getPost();
        $id = (int) strip_tags($post['id']);

        try {
            $turma = new TurmaModel();
            $sucesso = $turma->delete($id);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($turma->errors());
            }

            session()->setFlashdata('sucesso', 'Turma deletada com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro ao deletar a turma!']);
        }
    }

    /**
     * @route POST /turmas/import
     */
    public function import()
    {
        $post = $this->request->getFiles();
        $planilha = $post['planilha-alunos-turma'];
        $turmaId = $this->request->getPost('id'); 

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

            $nomeAluno = $rowData[2];
            $matricula = $rowData[3];
            $status = $rowData[4];

            $status_padrao = (strtolower($status) === 'Matriculado') ? 'Ativo' : (empty($status) ? 'Inativo' : $status);

            $dataRows[] = [
                'nome'      => $nomeAluno,
                'matricula'     => $matricula ,
                'status'    => $status_padrao,
                'turma_id'  => $turmaId 
            ];

        }

        $data['alunos'] = $dataRows;
        $data['import_completo'] = false;
        $data['turma_id'] = $turmaId;

        $mainContent = view('sys/aluno-importar-form', $data);
        return view('dashboard', ['content' => $mainContent]); 
    }

    /**
     * @route POST /turmas/importProcess
     */
    public function importProcess(){
        $selecionados = $this->request->getPost('selecionados');
        $turmaId = $this->request->getPost('turma_id');

        if (empty($selecionados)) {
            session()->setFlashdata('erros', ['Nenhum aluno foi selecionado para importação.']);
            return redirect()->to('sys/turmas');
        }
        
        $aluno = new AlunoModel();
        $updatedCount = 0;
        $errors = [];

        foreach ($selecionados as $alunoJson) {
            $alunoData = json_decode($alunoJson, true, 512, JSON_BIGINT_AS_STRING);
            $matricula = $alunoData['matricula'] ?? null;
            
            if ($matricula) {
                // Procura matricula
                $alunoExistente = $aluno->where('matricula', $matricula)->first();

                if ($alunoExistente) {
                    // atualiza o turma_id
                    $dadosParaAtualizar = ['turma_id' => $turmaId];
                    $sucesso = $aluno->update($alunoExistente['matricula'], $dadosParaAtualizar);

                    if (!$sucesso) {
                        $errosDoModelo = $aluno->errors();
                        $errors[] = "Erro ao atualizar a turma do aluno {$matricula}: " . implode(', ', $errosDoModelo);
                    } else {
                        $updatedCount++;
                    }
                } else {
                    // Se o aluno não for encontrado, adiciona um erro
                    $errors[] = "Aluno com matrícula {$matricula} não encontrado no banco de dados.";
                }
            }
        }

        $redirect = redirect()->to('sys/turmas');

        if ($updatedCount > 0) {
            $redirect->with('sucesso', "{$updatedCount} aluno(s) atualizado(s) com sucesso!");
        }

        if (!empty($errors)) {
            $redirect->with('erros', $errors);
        }
        
        return $redirect;
    }
}
