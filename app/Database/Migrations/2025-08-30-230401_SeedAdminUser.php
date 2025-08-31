<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Config;
use CodeIgniter\Database\Migration;
use Config\App;

class SeedAdminUser extends Migration
{
    public function up()
    {
        $seeder = \Config\Database::seeder();
        $seeder->call('App\Database\Seeds\AdminSeeder');
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->table('users')->where('secret', 'admin@admin.com')->delete();
    }
}
