<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AlunoModel;
use App\Models\TurmaModel;
use App\Models\CursoModel;

class AlunoController extends BaseController
{
    public function index()
    {
        $alunoModel = new AlunoModel();
        $turmaModel = new TurmaModel();
        $cursoModel = new CursoModel();

        $data['alunos'] = $alunoModel->orderBy('nome')->findAll();
        $data['turmas'] = $turmaModel
                        ->select('turmas.*, cursos.nome as curso_nome')
                        ->join('cursos', 'cursos.id = turmas.curso_id')
                        ->orderBy('turmas.nome')
                        ->findAll();

        $data['content'] = view('sys/aluno', $data);
        return view('dashboard', $data);
    }

    public function store()
    {
        $alunoModel = new AlunoModel();

        $post = $this->request->getPost();

        $input['nome'] = strip_tags($post['nome']);

        if ($aluno->insert($input)) {
            session()->setFlashdata('sucesso', 'Aluno cadastrado com sucesso!');
            return redirect()->to(base_url('/sys/aluno'));
        } else {
            return redirect()->to(base_url('/sys/aluno'))->with('erros', $aluno->errors())->withInput();
        }
    }

    public function update()
    {
        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);

        $aluno = new AlunoModel();
        if ($aluno->save($input)) {
            session()->setFlashdata('sucesso', 'Aluno atualizado com sucesso!');
            return redirect()->to(base_url('/sys/aluno'));
        } else {
            return redirect()->to(base_url('/sys/aluno'))->with('erros', $aluno->errors())->withInput();
        }
    }

    public function delete()
    {
        $post = $this->request->getPost();
        $id = (int) strip_tags($post['id']);

        $aluno = new AlunoModel();

        if ($aluno->delete($id)) {
            session()->setFlashdata('sucesso', 'Aluno deletado com sucesso!');
            return redirect()->to(base_url('/sys/aluno'));
        } else {
            session()->setFlashdata('erro', $aluno->errors());
            return redirect()->to(base_url('/sys/aluno'));
        }
    }
}