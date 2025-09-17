<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EnviarMensagensModel;
use App\Libraries\EvolutionAPI;

class ProcessaEnvioMensagens extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'command:processa-envio-mensagens';
    protected $description = 'Envia as mensagens dos agendamentos das refeiçõs';

    public function run(array $params)
    {
        $enviarMensagensModel = new EnviarMensagensModel();

        $mensagens = $enviarMensagensModel
                        ->where('status', 0)
                        ->orderBy('data_cadastro', 'ASC')
                        ->limit(3)
                        ->findAll();

         if (empty($mensagens)) {
            return; //Se não tiver nenhuma
        }

        CLI::write('Iniciando o processamento do envio da mensagem');
        $wpp = new EvolutionAPI();

        foreach ($mensagens as $mensagem) {

            try {
                
                $wpp->sendMessage($mensagem['destinatario'], $mensagem['mensagem']);
                
                $enviarMensagensModel->update($mensagem['id'], [
                    'status'     => 1, 
                    'data_envio' => date('Y-m-d H:i:s'),
                ]);
                CLI::write("Sucesso");
                

            } catch (\Exception $e) {
                
                $enviarMensagensModel->update($mensagem['id'], [
                    'status' => 0, // retorna p/ não enviada
                ]);

                CLI::write("Erro ao processar mensagem");
            }

            sleep(20);
        }

    }
}