<?php

namespace App\Controllers;

use App\Models\AlunoModel;
use App\Models\TurmaModel;

class AlunoController extends BaseController
{
    public function index()
    {
        $alunoModel = new AlunoModel();
        $turmaModel = new TurmaModel();
        
        $data = [
            'alunos' => $alunoModel->findAll(),
            'turmas' => $turmaModel->join('cursos', 'cursos.id = turmas.curso_id')->findAll(),
        ];

        return view('sys/aluno', $data);
    }
    
    public function criar()
    {
        $alunoModel = new AlunoModel();
        $postData = $this->request->getPost();
        
        if (!$alunoModel->validate($postData)) {
            return redirect()->back()->withInput()->with('errors', $alunoModel->errors());
        }

        $postData['status'] = ($postData['status'] == 'ativo') ? 1 : 0;

        if ($alunoModel->insert($postData)) {
            return redirect()->to('sys/alunos')->with('success', 'Aluno cadastrado com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('errors', ['Erro ao salvar o aluno.']);
        }
    }
}