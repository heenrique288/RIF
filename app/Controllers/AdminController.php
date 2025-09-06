<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Authentication\Passwords;

class AdminController extends BaseController
{

    protected $userModel;
    protected $table = 'auth_groups_users'; // tabela de associação entre usuários e grupos

    public function __construct()
    {
        $this->userModel = new UserModel();

    }

    public function getUserGroups($userId) // obtém os grupos de um usuário específico
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->where('user_id', $userId)
                  ->get()
                  ->getResult(); // retorna array de objetos
    }

    public function getGroupByName($groupName) // obtém um grupo pelo nome
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->where('group', $groupName)
                  ->get()
                  ->getRow(); // retorna o primeiro resultado como objeto
    }

    public function addUserToGroup($userId, $group, $createdAt = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups_users');

        if (empty($userId) || empty($group)) {
            log_message('error', 'ID do usuário ou grupo inválido.');
            return false;
        }

        $data = [
            'user_id' => $userId,
            'group' => $group,
            'created_at' => $createdAt ?? date('Y-m-d H:i:s'),
        ];

        try {
            $builder->insert($data);
            log_message('info', "Usuário $userId adicionado ao grupo $group com sucesso.");
            return true;
        } catch (\Exception $e) {
            log_message('error', "Erro ao adicionar usuário ao grupo: " . $e->getMessage());
            return false;
        }
    }

    public function index()
    {
        $usuarios = $this->gerenciarUsuarios();
        $data['usuarios'] = $usuarios;

        $data['content'] = view('sys/gerenciar-usuarios', ['usuarios' => $usuarios]);
        return view('dashboard', $data);
    }

    public function gerenciarUsuarios()
    {
        // Pega todos os usuários
        $usuarios = $this->userModel->select('id, username')->findAll();

        $db = \Config\Database::connect();
        $builderGrupos = $db->table('auth_groups_users'); // tabela de grupos
        $builderIdentities = $db->table('auth_identities'); // tabela de emails

        foreach ($usuarios as &$usuario) {
            // Pega o email do usuário na tabela auth_identities
            $emailRow = $builderIdentities
                ->select('secret')
                ->where('user_id', $usuario->id)
                ->where('type', 'email_password') // filtra só o e-mail
                ->get()
                ->getRow();

            $usuario->email = $emailRow ? $emailRow->secret : '';

            // Pega os grupos do usuário
            $grupos = $builderGrupos
                ->where('user_id', $usuario->id)
                ->get()
                ->getResult();

            $usuario->grupos = array_map(fn($grupo) => $grupo->group, $grupos);
        }

        return $usuarios;
    }

    public function registrarUsuario()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $existingUsername = $this->userModel->where('username', $data['username'])->first();
        $existingEmail = $this->userModel->db->table('auth_identities')
            ->where('type', 'email_password') // Tipo de identidade (e-mail)
            ->where('secret', $data['email']) // O e-mail está armazenado na coluna `secret`
            ->get()
            ->getRow();

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
            if (!$this->addUserToGroup($userId, $grupo)) {
                log_message('error', "Erro ao vincular o usuário $userId ao grupo $grupo.");
            } else {
                log_message('info', "Usuário $userId vinculado ao grupo $grupo com sucesso.");
            }
        }

        return redirect()->to('/sys/admin')->with('success', 'Usuário registrado com sucesso.');

    }
}