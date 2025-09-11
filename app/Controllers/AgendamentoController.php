<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\AlunoModel;
use App\Models\AlunoTelefoneModel;
use App\Models\EnviarMensagensModel;
use App\Models\ControleRefeicoesModel;

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
        $alunosSelecionados = $post['matriculas']; // array de matrículas
        $datasSelecionadas = $post['datas'];

        $controleRefeicoesModel = new ControleRefeicoesModel();
        $alunoModel = new AlunoModel();

        // Se selecionou "todos"
        if (in_array('todos', $alunosSelecionados)) {
            $alunos = $alunoModel->where('turma_id', $turma_id)->findAll();
            $matriculas = array_column($alunos, 'matricula');
        } else {
            $matriculas = $alunosSelecionados;
        }

        $dadosControle = [];
        foreach ($matriculas as $matricula) {
            foreach ($datasSelecionadas as $dataRefeicao) {
                if (!empty($dataRefeicao)) {
                    $dadosControle[] = [
                        'aluno_id' => $matricula,
                        'data_refeicao' => $dataRefeicao,
                        'status' => 0, // disponivel
                    ];
                }
            }
        }

        if (!empty($dadosControle )) {
            $controleRefeicoesModel->insertBatch($dadosControle );
            $this->createSendMessages($alunosSelecionados, $datasSelecionadas); 
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

    public function createSendMessages(array $matriculas, array $datasSelecionadas)
    {
        $alunoModel = new AlunoModel(); 
        $alunoTelefoneModel = new AlunoTelefoneModel();
        $enviarMensagensModel = new EnviarMensagensModel();
        
        foreach ($matriculas as $matricula) {
            $aluno = $alunoModel->find($matricula);
            $telefoneAluno = $alunoTelefoneModel->getTelefoneAtivoByAlunoId($matricula);
        
            if ($aluno && $telefoneAluno) {
                foreach ($datasSelecionadas as $dataRefeicao) {
                    
                    $nomeAluno = $aluno['nome'];
                    //$destinatario = $telefoneAluno['telefone'];
                    $destinatario = '69992599048'; 

                    $mensagem = "Prezado(o) {$nomeAluno}\n";
                    $mensagem .= "Confirme sua refeição para o dia {$dataRefeicao}\n";
                    $mensagem .= "*Digite 1* para sim, irei utilizar o beneficio no dia informado\n";
                    $mensagem .= "*Digite 2* para não, não irei utilizar o beneficio no dia informado";

                    $enviarMensagensModel->insert([
                        'destinatario' => $destinatario,
                        'mensagem'     => $mensagem,
                        'status'       => 0, // Pendente
                    ]);
                }
            }
        }
    }
}
