<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyEnviaMensagens extends Migration
{
    public function up()
    {
        $fields = [
            'data_cadastro' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('envia_mensagens', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('envia_mensagens', 'data_cadastro');
    }
}
