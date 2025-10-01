<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\EnviarMensagensModel;
use App\Models\ControleRefeicoesModel;

class WebhookController extends BaseController
{
    public function index()
    {
        //
    }

    public function response()
    {
        $mensagemModel = new EnviarMensagensModel();
        $refeicaoModel = new ControleRefeicoesModel();

        //a resposta do aluno
        $dados = $this->request->getJSON(true); 

        $primeiraMensagem = $dados['messages'][0];

        $destinatarioSujo = $dados['data']['key']['remoteJid'] ?? null;
        $destinatario = str_replace('@s.whatsapp.net', '', $destinatarioSujo);
        $resposta = trim($dados['data']['message']['conversation'] ?? '');

        $mensagem = $mensagemModel
            ->where('destinatario', $destinatario)
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->first();

        //atualizar a tabela de mensagens para recebido
        $mensagemModel->update($mensagem['id'], ['status' => 2]);

        $refeicao = $refeicaoModel
            ->where('aluno_id', $destinatario)
            ->orderBy('id', 'DESC')
            ->first();

        if ($resposta === '1') {
            $refeicaoModel->update($refeicao['id'], ['status' => 1]);
            return $this->response->setJSON(['sucesso' => 'Refeição confirmada.']);

        } else if ($resposta === '2') {
            $refeicaoModel->update($refeicao['id'], ['status' => 3]);
            return $this->response->setJSON(['sucesso' => 'Refeição recusada.']);

        } else {
            return $this->response->setJSON([
                'mensagem' => 'Resposta recebida, mas não reconhecida.'
            ]);
        }
    }
}
