<?php

require_once 'Aluno.php'; // Inclui o modelo Aluno

class AlunoRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM alunos");
        $alunoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $alunos = [];
        foreach ($alunoData as $data) {
            $aluno = new Aluno();
            $aluno->setId($data['id']);
            $aluno->setNome($data['nome']);
            $aluno->setMatricula($data['matricula']);
            $aluno->setEmail($data['email']);
            $aluno->setSaldo($data['saldo']);
            $alunos[] = $aluno;
        }
        return $alunos;
    }

    public function save(Aluno $aluno) {
        if ($aluno->getId() === null) {
            // Insere um novo aluno
            $stmt = $this->pdo->prepare(
                "INSERT INTO alunos (nome, matricula, email, saldo) VALUES (:nome, :matricula, :email, :saldo)"
            );
        } else {
            // Atualiza um aluno existente (não implementado no seu código Java, mas é uma boa prática)
            $stmt = $this->pdo->prepare(
                "UPDATE alunos SET nome = :nome, matricula = :matricula, email = :email, saldo = :saldo WHERE id = :id"
            );
            $stmt->bindValue(':id', $aluno->getId());
        }

        $stmt->bindValue(':nome', $aluno->getNome());
        $stmt->bindValue(':matricula', $aluno->getMatricula());
        $stmt->bindValue(':email', $aluno->getEmail());
        $stmt->bindValue(':saldo', $aluno->getSaldo());

        return $stmt->execute();
    }
}

?>