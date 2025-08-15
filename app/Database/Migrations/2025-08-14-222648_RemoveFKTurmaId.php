<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveFKTurmaId extends Migration
{
    public function up()
    {
        try {
            $this->forge->dropForeignKey('alunos', 'alunos_turma_id_foreign');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // A migração pode continuar.
        }

        $this->forge->modifyColumn('alunos', [
            'turma_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, 
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('alunos', [
            'turma_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        try {
            $this->forge->addForeignKey('turma_id', 'turmas', 'id');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // A migração pode continuar.
        }
    }
}
