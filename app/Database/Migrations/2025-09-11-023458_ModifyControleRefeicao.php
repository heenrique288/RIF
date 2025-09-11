<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyControleRefeicao extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

         try {
            $this->forge->dropForeignKey('controle_refeicoes', 'controle_refeicoes_aluno_id_foreign');
        } catch (\Exception $e) {}


        $fields = [
            'aluno_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'unsigned'   => FALSE, 
            ],
        ];

        $this->forge->modifyColumn('controle_refeicoes', $fields);

        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'controle_refeicoes_aluno_id_foreign');
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        try {
            $this->forge->dropForeignKey('controle_refeicoes', 'controle_refeicoes_aluno_id_foreign');
        } catch (\Exception $e) {}

        $fields = [
            'aluno_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
            ],
        ];

        $this->forge->modifyColumn('controle_refeicoes', $fields);

        $this->forge->addForeignKey('aluno_id', 'alunos', 'id', '', '', 'controle_refeicoes_aluno_id_foreign');
        
        $this->db->enableForeignKeyChecks();
    }
}
