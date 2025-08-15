<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Gerando dados fictícios para a visualização
        $data = [
            'turmasHoje' => 12,
            'alunosConfirmados' => 250,
            'solicitacoesPendentes' => 32,
            'naoRetiradasUltimos30Dias' => 15,
            'graficoPassado' => [
                'labels' => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                'previstas' => [150, 180, 200, 190, 220, 100, 80],
                'confirmadas' => [140, 175, 195, 185, 215, 95, 75],
                'servidas' => [135, 170, 190, 180, 210, 90, 70],
                'canceladas' => [5, 5, 5, 5, 5, 5, 5],
            ],
            'graficoFuturo' => [
                'labels' => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                'previstas' => [230, 250, 260, 245, 270, 120, 90],
                'confirmadas' => [220, 240, 255, 235, 260, 115, 85],
            ],
            'isDashboard' => true, // Flag para indicar que esta é a página do dashboard
        ];
        
        // Retorna a view principal, passando os dados
        return view('dashboard', $data);
    }
}