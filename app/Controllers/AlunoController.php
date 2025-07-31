<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AlunoModel;

class AlunoController extends Controller
{
    protected $alunoModel;

    public function __construct()
    {
        $this->alunoModel = new AlunoModel();
    }

    // Método principal para exibir a lista de alunos
    public function index()
    {
        $data['alunos'] = $this->alunoModel->findAll();
        
        // para que o CodeIgniter procure o arquivo dentro da pasta 'sys'
        return view('sys/aluno', $data); 
    }
}
