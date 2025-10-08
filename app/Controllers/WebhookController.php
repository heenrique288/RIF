<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\EnviarMensagensModel;
use App\Models\ControleRefeicoesModel;
use App\Models\AlunoTelefoneModel;

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
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $evolutionAPI = new EvolutionAPI();

        //a resposta do aluno
        $dados = $this->request->getJSON(true); 

        log_message('info', 'Webhook recebido -> ' . json_encode($dados));

        if (!isset($dados['event'])) {
            return $this->response->setJSON(['status' => 'evento inválido']);
        }

        $evento = $dados['event'];
        $data = $dados['data'];

        if ($evento === 'messages.upsert') {

            if (isset($data['key']['fromMe']) && $data['key']['fromMe'] === true) {
                return $this->response->setJSON(['status' => 'mensagem do bot ignorada']);
            }

            if (!isset($data['message']['conversation'])) {
                return $this->response->setJSON(['status' => 'mensagem sem texto']);
            }

            $destinatarioSujo = $data['key']['remoteJid'];
            $destinatarioCompleto = str_replace('@s.whatsapp.net', '', $destinatarioSujo); 
            $ddd_e_numero  =  substr($destinatarioCompleto, 2); //sem o dd de pais por enquanto

            $ddd = substr($ddd_e_numero, 0, 2); 
            $numero = substr($ddd_e_numero, 2);

            if (strlen($numero) === 8) {
                $destinatario = $ddd . '9' . $numero;
            } else {
                $destinatario = $ddd_e_numero;
            }
            
            $resposta = trim($data['message']['conversation']);

            $alunoTelefone = $alunoTelefoneModel
                ->where('telefone', $destinatario)
                ->first();

            $alunoMatricula = $alunoTelefone['aluno_id'];

            $mensagem = $mensagemModel
                ->where('destinatario', $destinatario)
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->first();

            //atualizar a tabela de mensagens para recebido
            $mensagemModel->update($mensagem['id'], ['status' => 2]);

            $refeicao = $refeicaoModel
                ->where('aluno_id', $alunoMatricula)
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

            $evolutionAPI->sendText($destinatario, $mensagemRetorno);
            return $this->response->setJSON(['mensagem' => 'Processamento concluído: ' . $mensagemRetorno]);
        }
        else{
            return $this->response->setJSON(['status' => 'demais eventos']);
        }

        
    }
}
