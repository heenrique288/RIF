<?php

namespace App\Models;

use CodeIgniter\Model;

class TurmaModel extends Model
{
    protected $table            = 'turmas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'nome',
        'curso_id',
    ];

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
    protected $validationRules      = [
        'nome'     => 'required|min_length[3]|max_length[96]',
        'curso_id' => 'required|numeric'
    ];

    protected $validationMessages   = [
        'nome'     => [
            'required' => 'Informe o nome da turma',
            'min_length' => 'O nome é muito curto.',
            'max_length' => 'O nome é muito longo.',
        ],
        'curso_id' => [
            'required' => 'Informe o curso da turma',
        ]
    ];

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
    protected array $turmasCache = []; // Cache interno para pegar as turmas com curso

    // Função para pegar nome das turma com curso, função que será chamada lá no AgendamentoController.php
    public function getNomeTurmaComCurso(int $turmaId): string
    {
        if (isset($this->turmasCache[$turmaId])) {
            return $this->turmasCache[$turmaId];
        }

        $turma = $this->select('turmas.nome as nome_turma, cursos.nome as nome_curso')
                    ->join('cursos', 'cursos.id = turmas.curso_id', 'left')
                    ->find($turmaId);

        $nome = $turma ? "{$turma['nome_turma']} - {$turma['nome_curso']}" : 'Turma Desconhecida';
        $this->turmasCache[$turmaId] = $nome;
        return $nome;
    }

}
