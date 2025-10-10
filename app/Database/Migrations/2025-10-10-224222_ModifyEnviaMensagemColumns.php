<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyEnviaMensagemColumns extends Migration
{
    public function up()
    {

        $this->forge->addColumn('envia_mensagens', [
            'categoria' => [
                'type'       => 'INT',
                'constraint' => 1,
                'null'       => false,
            ],
        ]);

    }

    public function down()
    {
        
        $this->forge->dropColumn('envia_mensagens', 'categoria');
    
    }
}
