<?php

namespace App\Models;

use CodeIgniter\Model;

class AlunoEmailModel extends Model
{
    protected $table          = 'alunos_emails';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields  = true;
    protected $allowedFields  = [
        'aluno_id',
        'email',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'aluno_id' => 'required|numeric',
        'email'    => 'required|valid_email|is_unique[alunos_emails.email,aluno_id,{aluno_id}]',
        'status'   => 'required|in_list[ativo,inativo]',
    ];

    protected $validationMessages = [
        'aluno_id' => [
            'required' => 'O ID do aluno é obrigatório.',
            'numeric'  => 'O ID do aluno deve ser um número.',
        ],
        'email' => [
            'required'    => 'O e-mail é obrigatório.',
            'valid_email' => 'Por favor, forneça um e-mail válido.',
            'is_unique'   => 'Este e-mail já está cadastrado para outro aluno.',
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list'  => 'O status deve ser "ativo" ou "inativo".',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['convertStatusToInteger'];
    protected $beforeUpdate = ['convertStatusToInteger'];

    protected function convertStatusToInteger(array $data)
    {
        if (isset($data['data']['status'])) {
            $data['data']['status'] = ($data['data']['status'] === 'ativo') ? 1 : 0;
        }
        return $data;
    }
}