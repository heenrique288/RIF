<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,

            // 'first_name',
        ];
    }

    public function getUsuariosComGrupos() // Seleciona todos os usuários com grupos OBS: Se não tiver grupo, também aparece na view "Nenhum grupo atribuído"
    {
        
        $usuarios = $this->select('users.id, users.username, auth_identities.secret as email')
                     ->join('auth_identities', 'auth_identities.user_id = users.id', 'left')
                     ->where('auth_identities.type', 'email_password')
                     ->findAll();

        $userGroupModel = model(UserGroupModel::class);

        foreach ($usuarios as &$usuario) {
            $grupos = $userGroupModel->getUserGroups($usuario->id);
            $usuario->grupos = array_column($grupos, 'group');
        }

        return $usuarios;
    }

    public function findByUsername(string $username, ?int $ignoreUserId = null) // Procura o nome do Usuário
    {
        $builder = $this->where('username', $username);
        if ($ignoreUserId) {
            $builder->where('id !=', $ignoreUserId);
        }
        return $builder->first();   
    }

    public function findByEmail(string $email, ?int $ignoreUserId = null) // Procura o E-mail do usuário
    {
        $builder = $this->db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $email);

        if ($ignoreUserId) {
            $builder->where('user_id !=', $ignoreUserId);
        }

        return $builder->get()->getRow();
    }


}
