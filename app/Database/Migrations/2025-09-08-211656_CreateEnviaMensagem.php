<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnviaMensagem extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE,
            ],
            'destinatario' => [
                'type'          => 'VARCHAR',
                'constraint'    => 11,
                'null'          => FALSE,
            ],
            'mensagem' => [
                'type'          => 'TEXT',
                'null'          => FALSE,
            ],
            'status' => [
                'type'          => 'TINYINT',
                'constraint'    => 1,
                'default'       => 0, 
                'null'          => FALSE,
            ],
            'data_envio' => [
                'type'          => 'DATETIME',
                'null'          => TRUE,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('envia_mensagens');
    }

    public function down()
    {
        $this->forge->dropTable('envia_mensagens');
    }
}
