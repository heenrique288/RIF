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
            0 => 'Disponível', 
            1 => 'Confirmada', 
            2 => 'Retirada', 
            3 => 'Cancelada',
        ];
        $motivoMap = [
            0 => 'Contraturno', 
            1 => 'Estágio', 
            2 => 'Treino', 
            3 => 'Projeto', 
            4 => 'Visita Técnica',
        ];

        $agendamentosDoBanco = $controleModel->orderBy('data_refeicao', 'ASC')->findAll();
        
        $alunoIds = array_unique(array_column($agendamentosDoBanco, 'aluno_id'));
        $alunosData = [];
        if (!empty($alunoIds)) {
            $alunos = $alunoModel->whereIn('matricula', $alunoIds)->findAll();
            foreach ($alunos as $aluno) {
                $alunosData[$aluno['matricula']] = $aluno;
            }
        }

        $agendamentosPorAluno = [];
        foreach ($agendamentosDoBanco as $agendamento) {
            $alunoId = $agendamento['aluno_id'];
            $motivo = $agendamento['motivo'];
            $status = $agendamento['status'];
            
            $chaveAlunoMotivo = $alunoId . '|' . $motivo;

            if (!isset($agendamentosPorAluno[$chaveAlunoMotivo])) {
                $agendamentosPorAluno[$chaveAlunoMotivo] = [
                    'aluno_id' => $alunoId,
                    'motivo'   => $motivo,
                    'status'   => $status,
                    'datas'    => []
                ];
            }
            $agendamentosPorAluno[$chaveAlunoMotivo]['datas'][] = $agendamento['data_refeicao'];
        }

        $agendamentosAgrupados = [];
        foreach ($agendamentosPorAluno as $dadosAluno) {
            sort($dadosAluno['datas']);
            $datasString = implode(',', $dadosAluno['datas']);

            $chaveFinal = md5($datasString . '|' . $dadosAluno['motivo']);

            if (!isset($agendamentosAgrupados[$chaveFinal])) {
                $agendamentosAgrupados[$chaveFinal] = [
                    'aluno_ids'      => [],
                    'datas_refeicao' => $dadosAluno['datas'],
                    'status'         => $dadosAluno['status'],
                    'motivo'         => $dadosAluno['motivo'],
                ];
            }
            $agendamentosAgrupados[$chaveFinal]['aluno_ids'][] = $dadosAluno['aluno_id'];
        }

        $agendamentosParaTabela = [];
        $turmasInfoCache = [];

        foreach ($agendamentosAgrupados as $agrupado) {
            $idsAlunosDoGrupo = $agrupado['aluno_ids'];
            $turmasDoGrupo = [];
            $alunosPorTurma = [];

            foreach ($idsAlunosDoGrupo as $alunoId) {
                if (isset($alunosData[$alunoId])) {
                    $aluno = $alunosData[$alunoId];
                    $turmaId = $aluno['turma_id'] ?? 'sem_turma';
                    $turmasDoGrupo[$turmaId][] = $aluno['nome'];
                }
            }

            $nomesAlunosDoGrupo = array_reduce($turmasDoGrupo, 'array_merge', []);
            $qtdTurmasUnicas = count($turmasDoGrupo);
            $tipo = 'aluno';
            $turmaOuAluno = $nomesAlunosDoGrupo[0] ?? 'Aluno não encontrado';
            $alunosParaModal = [];

            if ($qtdTurmasUnicas > 1) {
                $tipo = 'multi_turma';
                $turmaOuAluno = count($turmasDoGrupo) . " turmas selecionadas";
                
                foreach ($turmasDoGrupo as $turmaId => $nomesAlunos) {
                    if ($turmaId === 'sem_turma') {
                        $nomeTurmaFormatado = 'Alunos sem Turma';
                    } else {
                        if (!isset($turmasInfoCache[$turmaId])) {
                            $turmaComCurso = $turmaModel
                                ->select('turmas.nome as nome_turma, cursos.nome as nome_curso')
                                ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
                                ->find($turmaId);
                            $turmasInfoCache[$turmaId] = $turmaComCurso ? $turmaComCurso['nome_turma'] . ' - ' . $turmaComCurso['nome_curso'] : 'Turma Desconhecida';
                        }
                        $nomeTurmaFormatado = $turmasInfoCache[$turmaId];
                    }
                    $alunosParaModal[$nomeTurmaFormatado] = $nomesAlunos;
                }

            } elseif ($qtdTurmasUnicas === 1) {
                // Lógica para TURMA ÚNICA (ou um aluno só)
                $turmaIdDoGrupo = key($turmasDoGrupo);
                if (count($idsAlunosDoGrupo) > 1 && $turmaIdDoGrupo !== 'sem_turma') {
                    $tipo = 'turma';
                    if (!isset($turmasInfoCache[$turmaIdDoGrupo])) {
                        $turmaComCurso = $turmaModel
                            ->select('turmas.nome as nome_turma, cursos.nome as nome_curso')
                            ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
                            ->find($turmaIdDoGrupo);
                        $turmasInfoCache[$turmaIdDoGrupo] = $turmaComCurso ? $turmaComCurso['nome_turma'] . ' - ' . $turmaComCurso['nome_curso'] : 'Turma Desconhecida';
                    }
                    $turmaOuAluno = $turmasInfoCache[$turmaIdDoGrupo];
                }
            }

            $datasFormatadas = array_map(fn($dateStr) => $dateStr ? (new \DateTime($dateStr))->format('d/m/Y') : '', $agrupado['datas_refeicao']);

            $agendamentosParaTabela[] = [
                'tipo'              => $tipo,
                'turma_aluno'       => $turmaOuAluno,
                'data'              => implode('<br>', $datasFormatadas),
                'status'            => $statusMap[$agrupado['status']] ?? 'Desconhecido',
                'motivo'            => $motivoMap[$agrupado['motivo']] ?? 'Não especificado',
                'alunos'            => $nomesAlunosDoGrupo, // Para o modal de turma única
                'alunos_por_turma'  => $alunosParaModal, // Para o modal de múltiplas turmas
                'delete_info' => [
                    'aluno_ids' => $agrupado['aluno_ids'], // Envia todos os IDs para exclusão
                    'motivo'    => $agrupado['motivo'],
                    'datas'     => $agrupado['datas_refeicao']
                ]
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

        try {
            $post = $this->request->getPost();

            $turma_id = !empty($post['turma_id']) ? (int) strip_tags($post['turma_id']) : null;
            $matriculasString = is_array($post['matriculas']) && isset($post['matriculas'][0]) ? $post['matriculas'][0] : '';
            $datasString = is_array($post['datas']) && isset($post['datas'][0]) ? $post['datas'][0] : '';
            $status = strip_tags($post['status']);
            $motivo = strip_tags($post['motivo']);

            if (empty($matriculasString) || empty($datasString)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Selecione pelo menos um aluno e uma data.']);
            }

            $matriculas = explode(',', $matriculasString);
            $datas = explode(',', $datasString);

            $controleModel = new \App\Models\ControleRefeicoesModel();
            $dadosParaInserir = [];

            foreach ($matriculas as $matricula) {
                $matricula = trim($matricula);
                if (empty($matricula) || !is_numeric($matricula)) {
                    continue;
                }

                foreach ($datas as $data) {
                    $data = trim($data);
                    if (empty($data)) {
                        continue;
                    }

                    $dadosParaInserir[] = [
                        'aluno_id' => $matricula,
                        'data_refeicao' => $data,
                        'status' => $status,
                        'motivo' => $motivo,
                    ];
                }
            }

            if (!empty($dadosParaInserir)) {
                $controleModel->insertBatch($dadosParaInserir);
                // Se a funcionalidade de mensagens estiver pronta, pode descomentar
                // $this->createSendMessages($matriculas, $datas);
            }
            
            session()->setFlashdata('sucesso', 'Agendamento(s) criado(s) com sucesso!');
            return $this->response->setJSON(['success' => true]);

        } catch (\Exception $e) {
            log_message('error', '[AgendamentoController] Erro em create: ' . $e->getMessage() . ' na linha ' . $e->getLine());

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado no servidor. Verifique os logs para mais detalhes.'
            ]);
        }
    }

    public function delete()
    {
        $post = $this->request->getPost();
        
        $deleteInfo = json_decode($post['delete_info'], true);

        if (empty($deleteInfo)) {
            return redirect()->back()->with('erros', ['Dados para exclusão não fornecidos.']);
        }

        $controleModel = new ControleRefeicoesModel();
        
        try {
            $controleModel->db->transBegin();

            $query = $controleModel
                ->where('motivo', $deleteInfo['motivo'])
                ->whereIn('data_refeicao', $deleteInfo['datas']);

            // lógica usa sempre 'aluno_ids' que contém todas as matrículas do grupo
            if (!empty($deleteInfo['aluno_ids'])) {
                $query->whereIn('aluno_id', $deleteInfo['aluno_ids']);
            } else {
                // Fallback caso algo dê errado, para não apagar a tabela toda
                throw new \Exception("Nenhum ID de aluno fornecido para exclusão.");
            }
            
            $query->delete();

            if ($controleModel->db->transStatus() === false) {
                $controleModel->db->transRollback();
                return redirect()->back()->with('erros', ['Erro ao deletar o agendamento.']);
            }

            $controleModel->db->transCommit();
            session()->setFlashdata('sucesso', 'Agendamento deletado com sucesso!');
            return redirect()->back();

        } catch (\Exception $e) {
            $controleModel->db->transRollback();
            log_message('error', '[AgendamentoController] Erro em delete: ' . $e->getMessage());
            return redirect()->back()->with('erros', ['Ocorreu um erro interno ao deletar o agendamento.']);
        }
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
