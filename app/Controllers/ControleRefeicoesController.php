<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlunoModel;
use App\Models\ControleRefeicoesModel;

class ControleRefeicoesController extends BaseController
{
    public function index()
    {
        return view('sys/controle-refeicoes');
    }

    public function salvar()
    {
        //
    }

    public function atualizar()
    {
        //
    }

    public function deletar()
    {
        //
    }

    public function validar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Requisição inválida.'
            ]);
        }

        $data = $this->request->getJSON(true);
        $codigo = $data['codigo'] ?? null;

        if (!$codigo) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'QR code inválido.'
            ]);
        }

        $controle = new ControleRefeicoesModel();
        $controle_ref = $controle->find($codigo);

        if (!$controle_ref) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'QR Code inválido.'
            ]);
        }

        $alunoModel = new AlunoModel();
        $aluno = $alunoModel->find($controle_ref["aluno_id"]);

        helper('suap');

        $foto_url = "";
        try {
            $foto_url = suap_download_profile_pic($aluno['matricula']);
        } catch (\Exception) {
            $foto_url = "";
        }

        $podeServir = false;
        $mensagemStatus = "";
        switch ($controle_ref['status']) {
            case 1: // Pendente
                $podeServir = true;
                $mensagemStatus = "Refeição pendente, pronta para servir.";
                break;
            case 2: // Retirada / Já servida
                $podeServir = false;
                $mensagemStatus = "Esta refeição já foi retirada!";
                break;
            case 3: // Cancelada
                $podeServir = false;
                $mensagemStatus = "Esta refeição foi cancelada!";
                break;
            default:
                $podeServir = false;
                $mensagemStatus = "Status desconhecido da refeição.";
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'id' => $controle_ref['id'],
                'aluno' => [
                    'nome' => $aluno['nome'],
                    'foto_url' => $foto_url
                ],
                'data_refeicao' => date('d/m/Y', strtotime($controle_ref['data_refeicao'])),
                'status' => $controle_ref['status'],
                'pode_servir' => $podeServir,
                'mensagem_status' => $mensagemStatus
            ]
        ]);
    }

    public function tela_confirmacao()
    {
        $data["content"] = view('sys/confirmar-refeicao');
        return view("dashboard", $data);
    }

    public function confirmar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Requisição inválida.'
            ]);
        }

        $data = $this->request->getJSON(true);

        $id = $data['id'];
        if (!$id) {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => 'Solicitação inválida.'
            ]);
        }

        $controle = new ControleRefeicoesModel();

        $controle_ref = $controle->find($id);

        if (!$controle_ref) {
            return $this->response->setStatusCode(422)->setJSON(["error" => "Solicitação inválida."]);
        }

        if ($controle_ref["status"] != 1) {
            return $this->response->setStatusCode(422)->setJSON(["error" => "Essa refeição não foi confirmada."]);
        }

        try {
            $sucess = $controle->update($id, [
                "data_retirada" => date('Y-m-d H:i:s'),
                "status" => 2, //retirada
            ]);

            if (!$sucess) {
                return $this->response->setStatusCode(500)->setJSON(["error" => "Ocorreu um erro ao confirmar a refeição. Tente novamente!"]);
            }

            return $this->response->setStatusCode(200)->setJSON(["success" => true]);
        } catch (\Exception) {
            return $this->response->setStatusCode(500)->setJSON(["error" => "Ocorreu um erro ao confirmar a refeição. Tente novamente!"]);
        }
    }
}