<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use App\Models\GroupModel; // Importando a tabela 'auth_groups_users' para buscar um grupo pelo nome
use App\Models\UserGroupModel; // Importando a tabela 'auth_groups_users'
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Authentication\Passwords;

class AdminController extends BaseController
{

    protected $groupModel;
    protected $userModel;
    protected $userGroupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();  // Carregando o modelo de grupos
        $this->userGroupModel = new UserGroupModel();  // Carregando o modelo de associação de usuários a grupos
        $this->userModel = new UserModel();  // Carregando o modelo de usuários do CodeIgniter Shield

    }

    public function index()
    {
        $usuarios = $this->gerenciarUsuarios();
        $data['usuarios'] = $usuarios;
        $data['content'] = view('sys/gerenciar-usuarios', $data);

        return view('dashboard', $data);
    }

    public function gerenciarUsuarios()
    {
        $usuarios = $this->userModel->getUsuariosComGrupos(); // Pega todos os usuários com grupo -> Puxa do UserModel.php

        return $usuarios;
    }

    public function registrarUsuario()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $existingUsername = $this->userModel->findByUsername($data['username']); // Pega o método do UserModel.php
        $existingEmail = $this->userModel->findByEmail($data['email']); // Pega o método do UserModel.php


        $errorMessage = '';

        if (
            $existingUsername && $existingEmail
        ) {
            $errorMessage = 'Já existe um usuário com este username e e-mail.';
        } elseif ($existingUsername) {
            $errorMessage = 'Já existe um usuário com este username.';
        } elseif ($existingEmail) {
            $errorMessage = 'Já existe um usuário com este e-mail.';
        }

        if ($errorMessage) {
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $user = new User($data);

        if (!$this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        // Obtém o ID do usuário recém-criado
        $userId = $this->userModel->getInsertID();

        // Adiciona o usuário a um grupo (se fornecido)
        $grupo = $this->request->getPost('grupo');

        if ($grupo) {
            if (!$this->userGroupModel->addUserToGroup($userId, $grupo)) {
                log_message('error', "Erro ao vincular o usuário $userId ao grupo $grupo.");
            } else {
                log_message('info', "Usuário $userId vinculado ao grupo $grupo com sucesso.");
            }
        }

        return redirect()->to('/sys/admin')->with('success', 'Usuário registrado com sucesso.');

    }

    public function excluirPermanentemente() 
    {
        $request = service('request');

        $userId = $request->getPost('user_id');
        $adminPassword = $request->getPost('admin_password');

        if(!$userId || !$adminPassword) {
            return redirect()->back()->with('error', ['Dados inválidos']);
        }

        $admin = auth()->user();

        if(!$admin) {
            return redirect()->back()->with('error', ['Você precisa estar autenticado para excluir um usuário']);
        }

        if(!password_verify($adminPassword, $admin->password_hash)) {
            return redirect()->back()->with('error', ['Senha incorreta! A exclusão foi cancelada']);
        }

        $userModel = new \CodeIgniter\Shield\Models\UserModel();
        $user = $userModel->find($userId);

        if(!$user) {
            return redirect()->back()->with('error', ['Usuário não encontrado']);
        }

        $userModel->delete($userId, true); // Exclusão permanente

        return redirect()->back()->with('success', 'Usuário excluído permanentemente com sucesso.');
    }

    // Método para alterar o grupo de um usuário
    public function alterarGrupoUsuario()
    {
        $userId = $this->request->getPost('user_id');
        $novoGrupo = $this->request->getPost('novo_grupo');

        log_message('info', "Tentando alterar grupo do usuário ID: $userId para $novoGrupo");

        if (!$userId || !$novoGrupo) {
            return redirect()->to('/sys/admin/')->with('error', 'Usuário ou grupo inválido.');
        }
        
        // Verifica se o usuário já pertence ao grupo
        $userGroup = $this->userGroupModel->where('user_id', $userId)->where('group', $novoGrupo)->first();

        // Se o usuário já pertence ao grupo, nada precisa ser feito
        if ($userGroup) {
            log_message('info', "Usuário $userId já pertence ao grupo '$novoGrupo'. Nenhuma alteração necessária.");
            return redirect()->to('/sys/admin/')->with('info', 'Usuário já pertence ao grupo.');
        }

        // Se o usuário pertence a um grupo que não está mais no banco, não gera erro, só substitui
        $userCurrentGroup = $this->userGroupModel->where('user_id', $userId)->first();

        if ($userCurrentGroup && !$this->groupModel->getGroupByName($userCurrentGroup['group'])) {
            log_message('info', "Usuário $userId pertence a um grupo não registrado no banco. Substituindo o grupo.");
        }

        // Remove o usuário de todos os grupos antes de adicionar ao novo grupo
        $this->userGroupModel->where('user_id', $userId)->delete();

        // Adiciona o usuário ao novo grupo
        $result = $this->userGroupModel->addUserToGroup($userId, $novoGrupo);

        if (!$result) {
            log_message('error', "Falha ao alterar grupo do usuário: $userId para $novoGrupo");
            return redirect()->to('/sys/admin/')->with('error', 'Erro ao atualizar grupo do usuário.');
        }

        log_message('info', "Grupo do usuário $userId alterado para $novoGrupo com sucesso.");
        return redirect()->to('/sys/admin/')->with('success', 'Grupo do usuário alterado com sucesso!');
    }

    public function atualizarUsuario()
    {
        $request = service('request');

        // Captura os dados do formulário
        $userId         = $request->getPost('user_id');
        $username       = $request->getPost('username');
        $email          = $request->getPost('email');
        $adminPassword  = $request->getPost('admin_password');

        // Valida se os campos foram preenchidos
        if (!$userId || !$username || !$email || !$adminPassword) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Todos os campos são obrigatórios.']);
        }

        // Obtém o usuário logado (admin)
        $admin = auth()->user();

        if (!$admin) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Usuário não autenticado.']);
        }

        // Verifica se a senha do admin está correta
        if (!password_verify($adminPassword, $admin->password_hash)) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Senha do administrador incorreta.']);
        }

        // Busca o usuário no banco de dados
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Usuário não encontrado.']);
        }

        $existingUsername = $this->userModel->findByUsername($username, $userId); // Pega o método do UserModel.php
        $existingEmail = $this->userModel->findByEmail($email, $userId); // Pega o método do UserModel.php

        // Verifica se há conflitos de username ou e-mail
        if ($existingUsername && $existingEmail) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Já existe um usuário com este username e e-mail.']);
        } elseif ($existingUsername) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Já existe um usuário com este username.']);
        } elseif ($existingEmail) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Já existe um usuário com este e-mail.']);
        }

        // Atualiza os dados do usuário
        $user->username = $username;
        $user->email = $email;

        // Tenta salvar as alterações
        if (!$this->userModel->save($user)) {
            return redirect()->to('/sys/admin/')
                ->with('error', ['Erro ao atualizar usuário.']);
        }

        return redirect()->to('/sys/admin/')
            ->with('success', 'Usuário atualizado com sucesso!');
    }
}