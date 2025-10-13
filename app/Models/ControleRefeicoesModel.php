<?php

namespace App\Models;

use CodeIgniter\Model;

class ControleRefeicoesModel extends Model
{
    protected $table            = 'controle_refeicoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['aluno_id', 'data_refeicao', 'data_confirmacao', 'data_retirada', 'status', 'motivo'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    //
    //
    // FUNÇÕES DO MÉTODO INDEX() DO CONTROLLER --> AgendamentoController.php
    //
    //
    public function getAgendamentosAgrupados(): array
    {
        $agendamentos = $this->orderBy('data_refeicao', 'ASC')->findAll();

        $agendamentosPorAluno = [];
        foreach ($agendamentos as $agendamento) {
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

        return $agendamentosAgrupados;
    }

    //Função que pega os agendamentos da tabela para chamar no index() do AgendamentoController.php
    public function getAgendamentosParaTabela(AlunoModel $alunoModel, TurmaModel $turmaModel): array
    {
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

        // Busca os agendamentos individuais
        $agendamentos = $this->select('
                controle_refeicoes.id,
                controle_refeicoes.data_refeicao,
                controle_refeicoes.status,
                controle_refeicoes.motivo,
                alunos.matricula AS aluno_matricula,
                alunos.nome AS aluno_nome,
                alunos.turma_id,
                turmas.nome AS turma_nome,
                cursos.nome AS curso_nome
            ')
            ->join('alunos', 'alunos.matricula = controle_refeicoes.aluno_id', 'left')
            ->join('turmas', 'turmas.id = alunos.turma_id', 'left')
            ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
            ->orderBy('controle_refeicoes.data_refeicao', 'DESC')
            ->findAll();

        $result = [];
        foreach ($agendamentos as $a) {
            $turmaCompleta = trim(($a['turma_nome'] ?? '') . ' - ' . ($a['curso_nome'] ?? ''));
            $result[] = [
                'id'            => $a['id'],
                'turma_aluno'   => $a['aluno_nome'] ?? 'Sem nome',
                'turma'         => $turmaCompleta ?: 'Sem turma',
                'data'          => $a['data_refeicao'] ? (new \DateTime($a['data_refeicao']))->format('d/m/Y') : '',
                'status'        => $statusMap[$a['status']] ?? 'Desconhecido',
                'motivo'        => $motivoMap[$a['motivo']] ?? 'Não especificado',
                'alunos'        => [$a['aluno_nome'] ?? 'Sem nome'], // array com apenas 1 aluno
                'alunos_por_turma' => [$turmaCompleta ?: 'Sem turma' => [$a['aluno_nome'] ?? 'Sem nome']],
                'delete_info'   => [
                    'aluno_ids' => [$a['aluno_matricula']],
                    'motivo'    => $a['motivo'],
                    'datas'     => [$a['data_refeicao']],
                ]
            ];
        }

        return $result;
    }



    // Fução para retornar para a o controller os dadaos. --> AgendamentoController.php
    public function getViewData(AlunoModel $alunoModel, TurmaModel $turmaModel): array
    {
        return [
            'agendamentos' => $this->getAgendamentosParaTabela($alunoModel, $turmaModel),
            'alunos'       => $alunoModel->orderBy('nome')->findAll(),
            'turmas'       => $turmaModel->select('turmas.id, turmas.nome as nome_turma, cursos.nome as nome_curso')
                                           ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
                                           ->orderBy('turmas.nome')
                                           ->findAll()
        ];
    }

    //
    // FUNÇÃO COMO O MÉTODO CREATE() DO CONTROLLER --> AgendamentoController.php
    //

    public function createAgendamentos(array $matriculas, array $datas, string $status, string $motivo): bool
    {
        if (empty($matriculas) || empty($datas)) {
            return false;
        }

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
                    'aluno_id'      => $matricula,
                    'data_refeicao' => $data,
                    'status'        => $status,
                    'motivo'        => $motivo,
                ];
            }
        }

        if (!empty($dadosParaInserir)) {
            return (bool) $this->insertBatch($dadosParaInserir);
        }

        return false;
    }

    //
    // FUNÇÃO DO MÉTODO UPDATE() DO CONTROLLER. --> AgendamentoController.php
    //

    public function updateAgendamentos(
        array $originalAlunoIds,
        array $originalDatas,
        string $originalMotivo,
        array $newMatriculas,
        array $newDatas,
        string $newStatus,
        string $newMotivo
    ): bool {
        if (empty($newMatriculas) || empty($newDatas)) {
            return false;
        }

        $this->db->transBegin();

        try {
            // Deleta os registros antigos
            $this->where('motivo', $originalMotivo)
                ->whereIn('data_refeicao', $originalDatas)
                ->whereIn('aluno_id', $originalAlunoIds)
                ->delete();

            // Prepara os novos dados
            $dadosParaInserir = [];
            foreach ($newMatriculas as $matricula) {
                foreach ($newDatas as $data) {
                    $dadosParaInserir[] = [
                        'aluno_id'      => trim($matricula),
                        'data_refeicao' => trim($data),
                        'status'        => $newStatus,
                        'motivo'        => $newMotivo,
                    ];
                }
            }

            if (!empty($dadosParaInserir)) {
                $this->insertBatch($dadosParaInserir);
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();
            return true;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[ControleRefeicoesModel] Erro em updateAgendamentos: ' . $e->getMessage());
            return false;
        }
    }

    //
    // FUNÇÃO PARA O METEDO DELETE() DO CONTROLLER --> AgendamentoController.php
    //

    public function deleteAgendamentos(array $alunoIds, array $datas, string $motivo): bool
    {
        if (empty($alunoIds) || empty($datas)) {
            log_message('error', '[ControleRefeicoesModel] deleteAgendamentos chamado sem IDs ou datas.');
            return false;
        }

        $this->db->transBegin();

        try {
            $this->where('motivo', $motivo)
                ->whereIn('data_refeicao', $datas)
                ->whereIn('aluno_id', $alunoIds)
                ->delete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();
            return true;

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[ControleRefeicoesModel] Erro em deleteAgendamentos: ' . $e->getMessage());
            return false;
        }
    }

}