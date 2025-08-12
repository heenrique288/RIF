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
        'nome'     => 'required|min_length[3]|max_length[255]',
        'curso_id' => 'required|is_not_unique[cursos.id]'
    ];

    protected $validationMessages   = [
        'nome'     => [
            'required' => 'Campo Obrigatório',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres',
            'max_length' => 'O nome deve ter no máximo 255 caracteres',
        ],
        'curso_id' => [
            'required' => 'Campo Obrigatório',
            'is_not_unique' => 'Esse curso já foi cadastrado',
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
}
