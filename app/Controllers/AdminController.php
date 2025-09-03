<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        $data['content'] = view('sys/gerenciar-usuarios');
        return view('dashboard', $data);
    }
}