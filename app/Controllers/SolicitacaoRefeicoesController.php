<?php

namespace App\Controllers;

use App\Models\SolicitacaoRefeicoesModel;
use App\Models\TurmaModel;
use Exception;

class SolicitacaoRefeicoesController extends BaseController
{
    private $indexRoute = 'sys/solicitacoes';

    private function redirectToIndex(?array $erros = [])
    {
        if (gettype($erros) != "array" || count($erros) < 1) {
            return redirect()->to(base_url($this->indexRoute));
        }
        return redirect()->to(base_url($this->indexRoute))->with('erros', $erros)->withInput();
    }

    public function index()
    {
        $solicitacoes = new SolicitacaoRefeicoesModel();
        $turmas = new TurmaModel();

        $data['solicitacoes'] = $solicitacoes->orderBy('id')->findAll();
        $data['turmas'] = $turmas->orderBy('nome')->findAll();

        $data['content'] = view('sys/solicitacoes', $data);
        return view('dashboard', $data);
    }

    /**
     * Cadastra uma nova solicitacao no sistema.
     *
     * @route POST sys/solicitacoes/create
     */
    public function create()
    {
        $solicitacao = new SolicitacaoRefeicoesModel();

        $post = $this->request->getPost();

        $input['turma_id'] = (int) strip_tags($post['turma_id']);
        $input['data_refeicao'] = strip_tags($post['data_refeicao']);
        $input['crc'] = strip_tags($post['crc']);
        $input['status'] = 0; //por padrão, significa "pendente"
        $input['codigo'] = (int) strip_tags($post['codigo']);
        $input['justificativa'] = strip_tags($post['justificativa']);

        try {
            if ($solicitacao->insert($input)) {
                session()->setFlashdata('sucesso', 'Solicitação cadastrada com sucesso!');
                return $this->redirectToIndex();
            } else {
                return $this->redirectToIndex($solicitacao->errors());
            }
        } catch (Exception $e) {
            return $this->redirectToIndex(['Ocorreu um erro ao cadastrar a solicitação!']);
        }
    }

    /**
     * Atualiza os dados de uma solicitacao.
     * 
     * @route POST sys/solicitacoes/update
     */
    public function update()
    {
        $post = $this->request->getPost();

        $input['id'] = (int) strip_tags($post['id']);
        $input['turma_id'] = (int) strip_tags($post['turma_id']);
        $input['data_refeicao'] = strip_tags($post['data_refeicao']);
        $input['crc'] = strip_tags($post['crc']);
        $input['status'] = (int) strip_tags($post['status']);
        $input['codigo'] = (int) strip_tags($post['codigo']);
        $input['justificativa'] = strip_tags($post['justificativa']);

        try {
            $solicitacao = new SolicitacaoRefeicoesModel();
            if ($solicitacao->save($input)) {
                session()->setFlashdata('sucesso', 'Solicitação atualizada com sucesso!');
                return $this->redirectToIndex();
            } else {
                session()->setFlashdata('erro', $solicitacao->errors());
                return $this->redirectToIndex($solicitacao->errors());
            }
        } catch (\Exception $e) {
            return $this->redirectToIndex(['Ocorreu um erro ao editar a solicitação!']);
        }
    }

    /**
     * Deleta uma solicitacao.
     * 
     * @route POST sys/solicitacoes/delete
     */
    public function delete()
    {
        $post = $this->request->getPost();

        $id = (int) strip_tags($post['id']);

        $solicitacao = new SolicitacaoRefeicoesModel();

        try {
            if ($solicitacao->delete($id)) {
                session()->setFlashdata('sucesso', 'Solicitação deletada com sucesso!');
                return $this->redirectToIndex();
            } else {
                session()->setFlashdata('erro', $solicitacao->errors());
                return $this->redirectToIndex($solicitacao->errors());
            }
        } catch (Exception $e) {
            return $this->redirectToIndex(['Ocorreu um erro ao deletar a solicitação!']);
        }
    }
}
