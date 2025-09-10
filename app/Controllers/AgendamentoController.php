<?php

namespace App\Controllers;

use App\Models\TurmaModel;
use App\Models\AlunoModel;
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
        
        $turma_id = !empty($post['turma_id']) ? (int) strip_tags($post['turma_id']) : null;
        
        $matriculasString = is_array($post['matriculas']) && isset($post['matriculas'][0]) ? $post['matriculas'][0] : '';
        $datasString = is_array($post['datas']) && isset($post['datas'][0]) ? $post['datas'][0] : '';
        
        $status = strip_tags($post['status']);
        $motivo = strip_tags($post['motivo']);
        
        $matriculas = explode(',', $matriculasString);
        $datas = explode(',', $datasString);

        if (empty($matriculasString) || empty($datasString)) {
             return $this->response->setJSON(['success' => false, 'message' => 'Selecione pelo menos um aluno e uma data.']);
        }

        $controleModel = new \App\Models\ControleRefeicoesModel();

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

                $agendamento = [
                    'aluno_id' => $matricula,
                    'data_refeicao' => $data,
                    'status' => $status,
                    'motivo' => $motivo,
                ];

                $controleModel->insert($agendamento);
            }
        }
        
        session()->setFlashdata('sucesso', 'Agendamento(s) criado(s) com sucesso!');
        return $this->response->setJSON(['success' => true]);
    }

    public function getAlunosByTurma($turma_id)
    {
        $alunoModel = new AlunoModel();
        $alunos = $alunoModel->where('turma_id', $turma_id)
            ->where('status', 1)
            ->findAll();
        return $this->response->setJSON($alunos);
    }
}