<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSolicitacaoRefeicoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => TRUE,
                'auto_increment'    => TRUE
            ],

            'turma_id' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => TRUE
            ],

            'data_refeicao' => [
                'type'              => 'DATE',
            ],

            'crc' => [
                'type'              => 'VARCHAR',
                'constraint'        => 96,
            ],

            'status' => [
                'type'              => 'INT',
            ],

            'codigo' => [
                'type'              => 'INT',
            ],

            'justificativa' => [
                'type'              => 'VARCHAR',
                'constraint'        => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('turma_id', 'turmas', 'id');
        $this->forge->createTable('solicitacao_refeicoes');
    }

    public function down()
    {
        $this->forge->dropTable('solicitacao_refeicoes', true, true);
    }
}
