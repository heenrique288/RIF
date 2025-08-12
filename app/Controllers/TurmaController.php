<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TurmaModel;
use App\Models\CursoModel;
use Exception;

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
}