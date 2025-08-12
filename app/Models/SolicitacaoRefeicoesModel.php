<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitacaoRefeicoesModel extends Model
{
    protected $table            = 'solicitacaoRefeicoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome'];

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
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero|max_length[11]',
        'turma_id' => 'required|is_natural_no_zero',
        'data_refeicao' => 'required|date',
        'crc' => 'required',
        'status' => 'required',
        'codigo' => 'required',
        'justificativa' => 'required',
    ];

    protected $validationMessages = [
        'turma_id' => [
            'required' => 'Informe a turma.',
            'is_natural_no_zero' => 'Informe um valor válido.',
        ],
        'data_refeicao' => [
            'required' => 'Informe a data da refeição.',
            'date' => 'Informe uma data válida.'
        ],
        'crc' => [
            'required' => 'Informe o crc da solicitação.',
        ],
        'status' => [
            'required' => 'Informe o status da solicitação.',
        ],
        'codigo' => [
            'required' => 'Informe o código da solicitação.',
        ],
        'justificativa' => [
            'required' => 'Informe a justificativa da solicitação.',
        ],
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
}
