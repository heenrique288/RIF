<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\EnviarMensagensModel;
use App\Models\ControleRefeicoesModel;

use App\Libraries\EvolutionAPI;


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
        $evolutionAPI = new EvolutionAPI();

        //a resposta do aluno
        $dados = $this->request->getJSON(true); 

        //Teste
        log_message('debug', 'Webhook recebido: ' . json_encode($dados));

        $primeiraMensagem = $dados['messages'][0];

        $destinatarioSujo = $dados['key']['remoteJid'];
        $destinatario = str_replace('@s.whatsapp.net', '', $destinatarioSujo);
        $resposta = trim($dados['message']['conversation']);

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

        $mensagemRetorno = '';

        if ($resposta === '1') {
            $refeicaoModel->update($refeicao['id'], ['status' => 1]);
            $mensagemRetorno = 'Refeição confirmada.';

        } else if ($resposta === '2') {
            $refeicaoModel->update($refeicao['id'], ['status' => 3]);
            $mensagemRetorno = 'Refeição recusada.';

        } else {
            $mensagemRetorno = 'Resposta recebida, mas não reconhecida.';
        }

        $evolutionAPI->sendText($destinatario, 'Obrigado, sua resposta foi registrada.');
        return $this->response->setJSON(['mensagem' => $mensagemRetorno]);
    }
}
