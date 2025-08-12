<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitacaoRefeicoesModel extends Model
{
    protected $table            = 'solicitacao_refeicoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['turma_id', 'data_refeicao', 'crc', 'status', 'codigo', 'justificativa'];

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
        'crc' => 'required|string',
        'status' => 'permit_empty|numeric',
        'codigo' => 'required|numeric',
        'justificativa' => 'required|string|max_length[255]|min_length[8]',
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
            'min_length' => 'A justificativa deve ter pelo menos 8 caracteres.',
            'max_length' => 'A justificativa deve ter no máximo 255 caracteres.',
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
