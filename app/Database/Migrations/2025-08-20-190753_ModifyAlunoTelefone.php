<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAlunoTelefone extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('alunos_telefones', [
            'telefone' => [
                'name'       => 'telefone',
                'type'       => 'VARCHAR',
                'constraint' => 11,
                'null'       => false,
            ],
            'aluno_id' => [
                'name'       => 'aluno_id',
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('alunos_telefones', [
            'telefone' => [
                'name'       => 'telefone',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'aluno_id' => [
                'name'       => 'aluno_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
