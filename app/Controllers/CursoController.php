<?php

namespace App\Controllers;

use App\Models\CursoModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class CursoController extends BaseController
{
    protected $baseRoute = 'sys/cursos';

    public function index()
    {
        $model = new CursoModel();
        $data['cursos'] = $model->orderBy('nome')->findAll();

        $data['content'] = view('sys/cursos', $data);
        return view('dashboard', $data);
    }

    /**
     * @route POST /cursos/create
     */
    public function create()
    {
        $post = $this->request->getPost();

        $input['nome'] = strip_tags($post['nome']);

        try {
            $curso = new CursoModel();
            $sucesso = $curso->insert($input);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($curso->errors());
            }

            session()->setFlashdata('sucesso', 'Curso cadastrado com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro ao cadastrar o curso!']);
        }
    }

    /**
     * @route POST /cursos/update
     */
    public function update()
    {
        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);


        try {
            $curso = new CursoModel();
            $sucesso = $curso->save($input);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($curso->errors());
            }

            session()->setFlashdata('sucesso', 'Curso atualizado com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (DatabaseException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return $this->redirectToBaseRoute(['Já existe um curso com este nome!']);
            }

            return $this->redirectToBaseRoute(['Ocorreu um erro ao editar o curso!']);
        } catch (\Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro inesperado ao editar o curso!']);
        }
    }

    /**
     * @route POST /cursos/delete
     */
    public function delete()
    {
        $post = $this->request->getPost();

        $id = (int) strip_tags($post['id']);

        try {
            $curso = new CursoModel();
            $sucesso = $curso->delete($id);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($curso->errors());
            }

            session()->setFlashdata('sucesso', 'Curso deletado com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um deletar ao editar o curso!']);
        }
    }
}
