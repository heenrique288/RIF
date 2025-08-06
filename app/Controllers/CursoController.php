<?php

namespace App\Controllers;

use App\Models\CursoModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class CursoController extends BaseController
{
    public function index()
    {
        $model = new CursoModel();
        $data['cursos'] = $model->orderBy('nome')->findAll();

        $data['content'] = view('sys/cursos', $data);
        return view('dashboard', $data);
    }

    /**
     * Cadastra um novo curso no sistema.
     *
     * @route POST /cursos/criar
     */
    public function store()
    {
        $curso = new CursoModel();

        $post = $this->request->getPost();

        $input['nome'] = strip_tags($post['nome']);

        try {
            if ($curso->insert($input)) {
                session()->setFlashdata('sucesso', 'Curso cadastrado com sucesso!');
                return redirect()->to(base_url('/sys/cursos'));
            } else {
                return redirect()->to(base_url('/sys/cursos'))->with('erros', $curso->errors())->withInput();
            }
        } catch (Exception $e) {
            return redirect()->to(base_url('/sys/cursos'))->with('erros', ['Ocorreu um erro ao cadastrar o curso!'])->withInput();
        }
    }

    /**
     * Atualiza os dados de um curso.
     * 
     * @route PUT /cursos/atualizar
     */
    public function update()
    {
        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['nome'] = strip_tags($post['nome']);

        try {
            $curso = new CursoModel();
            if ($curso->save($input)) {
                session()->setFlashdata('sucesso', 'Curso atualizado com sucesso!');
                return redirect()->to(base_url('/sys/cursos'));
            } else {
                return redirect()->to(base_url('/sys/cursos'))->with('erros', $curso->errors())->withInput();
            }
        } catch (DatabaseException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return redirect()->to(base_url('/sys/cursos'))->with('erros', ['Já existe um curso com este nome!'])->withInput();
            }

            return redirect()->to(base_url('/sys/cursos'))->with('erros', ['Ocorreu um erro ao editar o curso!'])->withInput();
        } catch (\Exception $e) {
            return redirect()->to(base_url('/sys/cursos'))->with('erros', ['Ocorreu um erro inesperado ao editar o curso!'])->withInput();
        }
    }

    /**
     * Deleta um curso.
     * 
     * @route DELETE /cursos/deletar
     */
    public function delete()
    {
        $post = $this->request->getPost();
        $id = (int) strip_tags($post['id']);

        $curso = new CursoModel();

        try {
            if ($curso->delete($id)) {
                session()->setFlashdata('sucesso', 'Curso deletado com sucesso!');
                return redirect()->to(base_url('/sys/cursos'));
            } else {
                session()->setFlashdata('erro', $curso->errors());
                return redirect()->to(base_url('/sys/cursos'));
            }
        } catch (Exception $e) {
            return redirect()->to(base_url('/sys/cursos'))->with('erros', ['Ocorreu um deletar ao editar o curso!'])->withInput();
        }
    }
}
