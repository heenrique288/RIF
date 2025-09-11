<?php

namespace App\Models;

use CodeIgniter\Model;

class EnviarMensagensModel extends Model
{
    protected $table            = 'envia_mensagens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['destinatario', 'mensagem', 'status', 'data_envio'];

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
        'id'            => 'permit_empty|is_natural_no_zero|max_length[11]',
        'destinatario'  => 'required|max_length[11]',
        'mensagem'      => 'required',
        'status'        => 'required|in_list[0,1]',
    ];
    
    protected $validationMessages   = [
        'destinatario' => [
            'required'   => 'O campo destinatário é obrigatório.',
            'max_length' => 'O destinatário deve ter no máximo 11 caracteres.',
        ],
        'mensagem' => [
            'required'   => 'O campo mensagem é obrigatório.',
        ],
        'status' => [
            'required' => 'O campo status é obrigatório.',
            'in_list'  => 'O status deve ser 0 (pendente) ou 1 (enviado).',
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
