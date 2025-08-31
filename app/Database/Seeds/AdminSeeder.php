<?php

namespace App\Database\Seeds;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminPassword = env('admin.defaultPassword', 'admin');

        $users = model(UserModel::class);

        // Verifica se o usuário admin já existe
        $existingUser = $users->where('username', 'admin')->first();

        if ($existingUser) {
            // Exclui o usuário admin se já existir
            $users->delete($existingUser->id, true);
        }

        $user = new User([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => $adminPassword,
        ]);

        $users->save($user);
    }
}
