<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
		$data['content'] = "Sem conteúdo, por enquanto";
        return view('dashboard', $data);
    }
}
