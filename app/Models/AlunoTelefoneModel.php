<?php

namespace App\Models;

use CodeIgniter\Model;

class AlunoTelefoneModel extends Model
{
    protected $table          = 'alunos_telefones';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields  = true;
    protected $allowedFields  = [
        'aluno_id',
        'telefone',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'aluno_id' => 'required|numeric',
        'telefone' => 'required|min_length[8]|max_length[20]|is_unique[alunos_telefones.telefone,aluno_id,{aluno_id}]',
        'status'   => 'required|in_list[ativo,inativo]',
    ];

    protected $validationMessages = [
        'aluno_id' => [
            'required' => 'O ID do aluno é obrigatório.',
            'numeric'  => 'O ID do aluno deve ser um número.',
        ],
        'telefone' => [
            'required'   => 'O telefone é obrigatório.',
            'min_length' => 'O telefone é muito curto.',
            'max_length' => 'O telefone é muito longo.',
            'is_unique'  => 'Este telefone já está cadastrado para outro aluno.',
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list'  => 'O status deve ser "ativo" ou "inativo".',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
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