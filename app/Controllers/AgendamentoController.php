<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\AlunoModel;

class AgendamentoController extends BaseController
{
    public function index()
    {
        $turmas = new TurmaModel();
        $alunos = new AlunoModel();

        $agendamentos = [
            [
                'id' => 1,
                'turma_aluno' => 'João',
                'data' => '2025-08-27',
                'crc' => '123456',
                'codigo' => '7890',
                'justificativa' => 'Refeição especial',
                'alunos' => ['João', 'Maria']
            ],
            [
                'id' => 2,
                'turma_aluno' => 'Turma A',
                'data' => '2025-08-28',
                'crc' => '654321',
                'codigo' => '0987',
                'justificativa' => 'Evento escolar',
                'alunos' => [] // Pode ser vazio se for agendamento para a turma toda
            ],
        ];

        $data['agendamentos'] = $agendamentos;
        $data['alunos'] = $alunos->orderBy('nome')->findAll();
        $data['turmas'] = $turmas->orderBy('nome')->findAll();
        $data['content'] = view('sys/agendamento', $data);

        return view('dashboard', $data);
    }

    public function create()
    {
        $post = $this->request->getPost();
        $turma_id = (int) strip_tags($post['turma_id']);
        $alunosSelecionados = $post['matricula']; // array de matrículas

        // Se selecionou "todos"
        if (in_array('todos', $alunosSelecionados)) {
            $alunoModel = new AlunoModel();
            $alunos = $alunoModel->where('turma_id', $turma_id)->findAll();
            $matriculas = array_column($alunos, 'matricula');
        } else {
            $matriculas = $alunosSelecionados;
        }

        // Aqui você pode processar os agendamentos sem salvar
        foreach ($matriculas as $matricula) {
            // Exemplo: gerar um array para envio ou exibição
            $agendamento = [
                'turma_id' => $turma_id,
                'matricula' => $matricula,
                'data_refeicao' => strip_tags($post['data_refeicao'])
            ];
            // Pode salvar em sessão, enviar para PDF, ou fazer o que precisar
            session()->push('agendamentos_temp', $agendamento);
        }

        session()->setFlashdata('sucesso', 'Agendamento(s) processado(s) com sucesso!');
        return redirect()->back();
    }


    public function getAlunosByTurma($turma_id)
    {
        $alunoModel = new AlunoModel();

        $alunos = $alunoModel->where('turma_id', $turma_id)
            ->where('status', 1) // Seleciona os alunos ativos
            ->findAll();

        return $this->response->setJSON($alunos);
    }
}
