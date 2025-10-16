<?php

namespace App\Controllers;

use App\Models\CursoModel;
use App\Models\TurmaModel;
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

        if (!isset($post['id'])) {
            return $this->redirectToBaseRoute(['ID do curso não informado!']);
        }
        
        $id = (int) strip_tags($post['id']);

        $senha = $post['senha'] ?? null;

        try {

            // Verifica se o usuário está autenticado
            $usuario = auth()->user();
            if (!$usuario) {
                return $this->redirectToBaseRoute(['Você precisa estar autenticado para excluir um curso.']);
            }

            $turmaModel = new TurmaModel();
            $temTurmas = $turmaModel->where('curso_id', $id)->countAllResults() > 0;
            // Se o curso tiver turmas, exige senha
            if ($temTurmas) {
                if (!$senha) {
                    return $this->redirectToBaseRoute(['Por favor, informe sua senha para confirmar a exclusão.']);
                }

                if (!password_verify($senha, $usuario->password_hash)) {
                    return $this->redirectToBaseRoute(['Senha incorreta! A exclusão foi cancelada.']);
                }
            }

            $turmaModel->where('curso_id', $id)->delete();
            
            $curso = new CursoModel();
            $sucesso = $curso->delete($id);

            if (!$sucesso) {
                return $this->redirectToBaseRoute($curso->errors());
            }

            session()->setFlashdata('sucesso', 'Curso deletado com sucesso!');
            return $this->redirectToBaseRoute();
        } catch (Exception $e) {
            return $this->redirectToBaseRoute(['Ocorreu um erro ao deletar o curso!']);
        }
    }

    public function verificarTurmas($id)
    {
        $turmaModel = new TurmaModel();

        // Verifica se existem turmas associadas ao curso
        $temTurmas = $turmaModel->where('curso_id', $id)->countAllResults() > 0;

        return $this->response->setJSON(['temTurmas' => $temTurmas]);
    }

}
