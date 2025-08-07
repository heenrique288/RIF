<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Alunos</title>
</head>
<body>
    <div class="container">
        <h1>Alunos Cadastrados</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#alunoModal">
            Novo Aluno
        </button>
        
        <div class="table-container">
            <?php if (isset($alunos) && !empty($alunos)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Turma ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $aluno): ?>
                            <tr>
                                <td><?= esc($aluno['matricula']) ?></td>
                                <td><?= esc($aluno['nome']) ?></td>
                                <td><?= esc($aluno['turma_id']) ?></td>
                                <td><?= esc($aluno['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum aluno encontrado no banco de dados.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?= $this->include('components/alunos/modal_cad_aluno', ['turmas' => $turmas]) ?>
</body>
</html>