<?php

require_once 'AlunoRepository.php'; // Inclui o repositório
require_once 'Aluno.php'; // Inclui o modelo

class AlunoController {
    private $alunoRepository;

    public function __construct(PDO $pdo) {
        $this->alunoRepository = new AlunoRepository($pdo);
    }

    public function handleRequest($action) {
        switch ($action) {
            case 'list':
                $this->listarAlunos();
                break;
            case 'new':
                $this->mostrarFormularioDeCadastro();
                break;
            case 'save':
                $this->salvarAluno();
                break;
            default:
                $this->listarAlunos(); // Padrão é listar alunos
                break;
        }
    }

    /**
     * Método para listar todos os alunos.
     * Mapeado para a URL: GET /alunos (simulado)
     */
    public function listarAlunos() {
        $alunos = $this->alunoRepository->findAll();
        // Passa os dados para a view
        include 'views/alunos/lista.php';
    }

    /**
     * Método para exibir o formulário de cadastro de um novo aluno.
     * Mapeado para a URL: GET /alunos/novo (simulado)
     */
    public function mostrarFormularioDeCadastro() {
        $aluno = new Aluno(); // Objeto Aluno vazio para o formulário
        // Passa os dados para a view
        include 'views/alunos/formulario.php';
    }

    /**
     * Método para salvar um novo aluno.
     * Mapeado para a URL: POST /alunos (simulado)
     */
    public function salvarAluno() {
        $aluno = new Aluno();
        // Preenche o objeto Aluno com os dados do POST
        if (isset($_POST['nome'])) {
            $aluno->setNome($_POST['nome']);
        }
        if (isset($_POST['matricula'])) {
            $aluno->setMatricula($_POST['matricula']);
        }
        if (isset($_POST['email'])) {
            $aluno->setEmail($_POST['email']);
        }
        if (isset($_POST['saldo'])) {
            $aluno->setSaldo((double)$_POST['saldo']);
        }

        $this->alunoRepository->save($aluno);
        header('Location: index.php?action=list'); // Redireciona o usuário para a página de listagem
        exit();
    }
}

?>