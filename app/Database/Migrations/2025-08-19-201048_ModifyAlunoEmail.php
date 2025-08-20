<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAlunoEmail extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('alunos_emails', [
            'aluno_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
            ],
        ]);

        $this->forge->modifyColumn('alunos_emails', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('alunos_emails', [
            'aluno_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->modifyColumn('alunos_emails', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
    }
}
