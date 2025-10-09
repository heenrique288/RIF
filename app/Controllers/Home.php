<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TurmaModel;
use App\Models\SolicitacaoRefeicoesModel;

class Home extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();
        $turmaModel = new TurmaModel();
        $solicitacaoModel = new SolicitacaoRefeicoesModel();

        $totalUsuarios = $userModel->countAllResults();
        $totalTurmas = $turmaModel->countAllResults();
        $solicitacoesPendentes = $solicitacaoModel->where('status', 0)->countAllResults();

        $alunosConfirmados = 250;
        
        $data = [
            'totalUsuarios'         => $totalUsuarios,
            'totalTurmas'           => $totalTurmas,
            'solicitacoesPendentes' => $solicitacoesPendentes,
            'alunosConfirmados'     => $alunosConfirmados,

            'graficoPassado' => [
                'labels'      => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                'previstas'   => [150, 180, 200, 190, 220, 100, 80],
                'confirmadas' => [140, 175, 195, 185, 215, 95, 75],
                'servidas'    => [135, 170, 190, 180, 210, 90, 70],
                'canceladas'  => [5, 5, 5, 5, 5, 5, 5],
            ],
            'graficoFuturo' => [
                'labels'      => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                'previstas'   => [230, 250, 260, 245, 270, 120, 90],
                'confirmadas' => [220, 240, 255, 235, 260, 115, 85],
            ],
        ];
        
        return view('dashboard', $data);
    }
}

