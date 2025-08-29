<?php

namespace App\Models;

use CodeIgniter\Model;

class AlunoModel extends Model
{
    protected $table            = 'alunos';
    protected $primaryKey       = 'matricula';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['matricula', 'nome', 'turma_id', 'status'];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'matricula' => 'required|numeric|min_length[5]|max_length[15]|is_unique[alunos.matricula,matricula,{id}]',
        'nome'      => 'required|min_length[3]|max_length[255]|is_unique[alunos.nome,nome,{id}]', 
        'status'    => 'required|in_list[ativo,inativo]',
    ];

    protected $validationMessages = [
        'matricula' => [
            'required'   => 'A matrícula é obrigatória.',
            'numeric'    => 'A matrícula deve conter apenas números.',
            'min_length' => 'A matrícula deve ter pelo menos 5 dígitos.',
            'max_length' => 'A matrícula deve ter no máximo 15 dígitos.',
            'is_unique'  => 'Esta matrícula já existe.',
        ],
        'nome' => [
            'required'   => 'O nome é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome deve ter no máximo 255 caracteres.',
            'is_unique'  => 'Este nome de aluno já existe.',
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list'  => 'O status deve ser "ativo" ou "inativo".',
        ],
    ];
    
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