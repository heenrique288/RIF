<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAlunosMatricula extends Migration
{
    public function up()
    {
        try {
            $this->forge->dropForeignKey('alunos', 'alunos_turma_id_foreign');
        } catch (\Exception $e) {}

        try {
            $this->forge->dropForeignKey('alunos_emails', 'alunos_emails_aluno_id_foreign');
        } catch (\Exception $e) {}
        
        try {
            $this->forge->dropForeignKey('alunos_telefones', 'alunos_telefones_aluno_id_foreign');
        } catch (\Exception $e) {}
        
        try {
            $this->forge->dropForeignKey('controle_refeicoes', 'controle_refeicoes_aluno_id_foreign');
        } catch (\Exception $e) {}

        $this->forge->modifyColumn('alunos', [
            'matricula' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'unique'     => true,
                'null'       => false,
            ],
        ]);
        
        $this->forge->addForeignKey('turma_id', 'turmas', 'id', '', '', 'alunos_turma_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'alunos_emails_aluno_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'alunos_telefones_aluno_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'controle_refeicoes_aluno_id_foreign');
    }

    public function down()
    {
        try {
            $this->forge->dropForeignKey('alunos', 'alunos_turma_id_foreign');
        } catch (\Exception $e) {}
        
        try {
            $this->forge->dropForeignKey('alunos_emails', 'alunos_emails_aluno_id_foreign');
        } catch (\Exception $e) {}
        
        try {
            $this->forge->dropForeignKey('alunos_telefones', 'alunos_telefones_aluno_id_foreign');
        } catch (\Exception $e) {}

        try {
            $this->forge->dropForeignKey('controle_refeicoes', 'controle_refeicoes_aluno_id_foreign');
        } catch (\Exception $e) {}

        $this->forge->modifyColumn('alunos', [
            'matricula' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addForeignKey('turma_id', 'turmas', 'id', '', '', 'alunos_turma_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'alunos_emails_aluno_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'alunos_telefones_aluno_id_foreign');
        $this->forge->addForeignKey('aluno_id', 'alunos', 'matricula', '', '', 'controle_refeicoes_aluno_id_foreign');
    }
}
