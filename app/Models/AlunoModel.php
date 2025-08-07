<?php

namespace App\Models;

use CodeIgniter\Model;

class AlunoModel extends Model
{
    protected $table = 'alunos';
    protected $primaryKey = 'matricula';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = ['matricula', 'nome', 'turma_id', 'status'];

    protected $validationRules = [
        'matricula' => 'required|max_length[20]|is_unique[alunos.matricula]',
        'nome'      => 'required|max_length[96]',
    ];

    protected $validationMessages = [
        'matricula' => [
            'is_unique' => 'Esta matrícula já existe.'
        ]
    ];
}