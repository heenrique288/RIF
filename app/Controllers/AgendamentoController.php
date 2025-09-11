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
        $turmaModel = new TurmaModel();
        $alunoModel = new AlunoModel();
        $controleModel = new ControleRefeicoesModel();

        $statusMap = [
            0 => 'Disponível', 1 => 'Confirmada', 2 => 'Retirada', 3 => 'Cancelada',
        ];
        $motivoMap = [
            0 => 'Contraturno', 1 => 'Estágio', 2 => 'Treino', 3 => 'Projeto', 4 => 'Visita Técnica',
        ];

        $agendamentosDoBanco = $controleModel->findAll();
        
        $alunoIds = array_unique(array_column($agendamentosDoBanco, 'aluno_id'));
        $alunoTurmaMap = [];
        if (!empty($alunoIds)) {
            $alunos = $alunoModel->whereIn('matricula', $alunoIds)->findAll();
            foreach ($alunos as $aluno) {
                $alunoTurmaMap[$aluno['matricula']] = $aluno['turma_id'] ?? null;
            }
        }

        $agendamentosAgrupados = [];

        foreach ($agendamentosDoBanco as $agendamento) {
            $alunoId = $agendamento['aluno_id'];
            $turmaId = $alunoTurmaMap[$alunoId] ?? 'sem_turma';
            $motivo = $agendamento['motivo'] ?? 'sem_motivo';
            
            $chave = md5($turmaId . '|' . $motivo);

            if (!isset($agendamentosAgrupados[$chave])) {
                $agendamentosAgrupados[$chave] = [
                    'aluno_ids' => [],
                    'datas_refeicao' => [],
                    'status' => $agendamento['status'],
                    'motivo' => $agendamento['motivo'],
                    'turma_id' => ($turmaId === 'sem_turma') ? null : $turmaId,
                ];
            }
            
            if (!in_array($alunoId, $agendamentosAgrupados[$chave]['aluno_ids'])) {
                $agendamentosAgrupados[$chave]['aluno_ids'][] = $alunoId;
            }
            if (!in_array($agendamento['data_refeicao'], $agendamentosAgrupados[$chave]['datas_refeicao'])) {
                $agendamentosAgrupados[$chave]['datas_refeicao'][] = $agendamento['data_refeicao'];
            }
        }

        $agendamentosParaTabela = [];
        foreach ($agendamentosAgrupados as $agrupado) {
            $idsAlunosDoGrupo = $agrupado['aluno_ids'];
            $nomesAlunosDoGrupo = !empty($idsAlunosDoGrupo)
                ? $alunoModel->whereIn('matricula', $idsAlunosDoGrupo)->findColumn('nome') ?? []
                : [];
            
            $turmaIdDoGrupo = $agrupado['turma_id'];
            $tipo = 'aluno';
            $turmaOuAluno = $nomesAlunosDoGrupo[0] ?? 'Aluno não encontrado';

            if ($turmaIdDoGrupo && count($idsAlunosDoGrupo) > 1) {
                $tipo = 'turma';
                $turmaComCurso = $turmaModel
                    ->select('turmas.nome as nome_turma, cursos.nome as nome_curso')
                    ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
                    ->find($turmaIdDoGrupo);
                if ($turmaComCurso) {
                    $turmaOuAluno = $turmaComCurso['nome_turma'] . ' - ' . $turmaComCurso['nome_curso'];
                } else {
                    $turmaOuAluno = 'Turma Desconhecida';
                }
            }

            $datasFormatadas = array_map(fn($dateStr) => $dateStr ? (new \DateTime($dateStr))->format('d/m/Y') : '', $agrupado['datas_refeicao']);

            $agendamentosParaTabela[] = [
                'tipo' => $tipo,
                'turma_aluno' => $turmaOuAluno,
                'data' => implode('<br>', $datasFormatadas),
                'status' => $statusMap[$agrupado['status']] ?? 'Desconhecido',
                'motivo' => $motivoMap[$agrupado['motivo']] ?? 'Não especificado',
                'alunos' => $nomesAlunosDoGrupo,
            ];
        }

        $data['agendamentos'] = $agendamentosParaTabela;
        $data['alunos'] = $alunoModel->orderBy('nome')->findAll();
        $data['turmas'] = $turmaModel
            ->select('turmas.id, turmas.nome as nome_turma, cursos.nome as nome_curso')
            ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
            ->orderBy('turmas.nome')
            ->findAll();
        $data['content'] = view('sys/agendamento', $data);

        return view('dashboard', $data);
    }
    
    public function create()
    {
        $this->response->setContentType('application/json');
        $post = $this->request->getPost();

        $turma_id = (int) strip_tags($post['turma_id']);
        $alunosSelecionados = $post['matriculas'];
        $datasSelecionadas = $post['datas'];

        $controleRefeicoesModel = new ControleRefeicoesModel();
        $alunoModel = new AlunoModel();

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
                        'status' => 0,
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
            ->where('status', 1)
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
