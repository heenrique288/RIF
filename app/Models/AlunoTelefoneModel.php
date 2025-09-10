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
    protected $allowedFields  = ['aluno_id', 'telefone', 'status'];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'aluno_id' => 'required|numeric',
        'telefone' => 'required|min_length[8]|max_length[20]|is_unique[alunos_telefones.telefone]',
        'status'   => 'required|in_list[ativo,inativo]',
    ];

    protected $validationMessages = [
        'telefone' => [
            'required'   => 'O telefone é obrigatório.',
            'is_unique'  => 'Este telefone já está cadastrado para outro aluno.',
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

    //Método utilizado no processSendMessages do Controller do AgendamentoController
    public function getTelefoneAtivoByAlunoId($aluno_id): ?array
    {
        return $this->where('aluno_id', $aluno_id)
                    ->where('status', 1) 
                    ->first();
    }
}