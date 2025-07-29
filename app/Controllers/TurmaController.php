<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TurmaModel;

class TurmaController extends BaseController
{
    public function index()
    {
        $turmas_model = new TurmaModel();

        $turmas = $turmas_model->findAll();

        $data['turmas'] = $turmas;

        $data['content'] = view('sys/turmas', $data);
        return view('dashboard', $data);
    }

    public function store()
    {
        $turmas_model = new TurmaModel();

        $post = $this->request->getPost();

        $input['nome'] = strip_tags($post['nome']);
        $input['curso_id'] = strip_tags($post['curso_id']);

        if($turmas_model->insert($input)){
            return redirect()->to(base_url('/sys/turmas/alert=sucessoCriar'));
        } else {
            return redirect()->to(base_url('/sys/turmas/alert=falhaCriar'));
        }
    }

    public function update()
    {
        $turmas_model = new TurmaModel();

        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);
        $input['curso_id'] = strip_tags($post['curso_id']);

        if($turmas_model->save($input)) {
            return redirect()->to(base_url('/sys/turmas/alert=sucessoAtualizar'));
        } else {
            return redirect()->to(base_url('/sys/turmas/alert=falhaAtualizar'));
        }
    }

    public function delete($turmaId)
    {
        $turmas_model = new TurmaModel();

        if($turmas_model>where('id', $turmaId)->delete()){
            return redirect()->to(base_url('/sys/turmas/alert=sucessoDeletar'));
        } else{
            return redirect()->to(base_url('/sys/turmas/alert=falhaDeletar'));
        }

    }
}
