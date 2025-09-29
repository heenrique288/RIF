<?php

namespace App\Controllers;

class AnaliseSolicitacaoController extends BaseController
{
    public function index()
    {
        $data['content'] = view('sys/analise-solicitacao');
        return view('dashboard', $data);
    }
}