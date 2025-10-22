<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyTurmaAddCodTurma extends Migration
{
    public function up()
    {
        $fields = [
            'codTurma' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'after'      => 'id', 
                'null'       => true,   
                'unique'     => true,   
            ],
        ];

        $this->forge->addColumn('turmas', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('turmas', 'codTurma');
    }
}
