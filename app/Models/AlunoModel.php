<?php

namespace App\Models;

use CodeIgniter\Model;

class AlunoModel extends Model
{
    // Define a tabela 'alunos'
    protected $table = 'alunos';
    
    // Define a chave primária como 'matricula', de acordo com sua migração.
    protected $primaryKey = 'matricula'; 
    
    // De acordo com a migração, a chave primária não é auto-incremento.
    protected $useAutoIncrement = false; 
    
    // Especifica o tipo de retorno para os métodos de busca.
    protected $returnType = 'array';
    
    // Define os campos que são permitidos serem inseridos ou atualizados.
    // Baseado na sua migração 'CreateAlunos.php'.
    // Adicionei o 'email' e 'saldo' por convenção, mas eles não estão na tabela principal.
    // Isso pode causar erros se você tentar inserir esses campos.
    // Para simplificar, vou remover esses campos por agora para evitar erros.
    protected $allowedFields = ['matricula', 'nome', 'turma_id', 'status'];

    // Timestamps: Deixando como 'false' já que a migração não os criou.
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    
    // Validação de dados (opcional, mas recomendado)
    protected $validationRules = [
        'matricula' => 'required|max_length[20]|is_unique[alunos.matricula]',
        'nome'      => 'required|max_length[96]',
    ];
    
    // Mensagens de erro de validação customizadas
    protected $validationMessages = [
        'matricula' => [
            'is_unique' => 'Esta matrícula já existe.'
        ]
    ];
    
    protected $skipValidation = false;
}
