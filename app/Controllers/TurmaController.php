<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TurmaModel;
use App\Models\CursoModel;

class TurmaController extends BaseController
{
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

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Acesso Negado');
        }

        $turmas_model = new TurmaModel();

        $rules = [
            'nome'     => 'required|min_length[3]|max_length[255]',
            'curso_id' => 'required|is_not_unique[cursos.id]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }
        $input = $this->validator->getValidated();

        if ($turmas_model->insert($input)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Turma cadastrada com sucesso!']);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => ['database' => 'Ocorreu um erro ao salvar no banco de dados.']]);
        }
    }

    public function update()
    {
        $turmas_model = new TurmaModel();

        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);
        $input['curso_id'] = strip_tags($post['curso_id']);

        if ($turmas_model->save($input)) {
            return redirect()->to(base_url('/sys/turmas'))->with('successo', 'Turma atualizada com sucesso!');
        } else {
            return redirect()->to(base_url('/sys/turmas'))->with('erro', 'Ocorreu um erro ao atualizar a turma.');
        }
    }

    public function delete()
    {
        $turmas_model = new TurmaModel();
        $turmaId = $this->request->getPost('id'); 

        if ($turmas_model->where('id', $turmaId)->delete()) {
            return redirect()->to(base_url('/sys/turmas'))->with('successo', 'Turma excluída com sucesso');
        } else {
            return redirect()->to(base_url('/sys/turmas'))->with('erro', 'Ocorreu um erro ao excluir a turma.');
        }
    }
}