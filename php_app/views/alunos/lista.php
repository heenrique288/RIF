<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Alunos</h1>
        <a href="index.php?action=new" class="btn btn-primary mb-3">Adicionar Novo Aluno</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Email</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alunos)): ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?= htmlspecialchars($aluno->getId()) ?></td>
                            <td><?= htmlspecialchars($aluno->getNome()) ?></td>
                            <td><?= htmlspecialchars($aluno->getMatricula()) ?></td>
                            <td><?= htmlspecialchars($aluno->getEmail()) ?></td>
                            <td><?= htmlspecialchars(number_format($aluno->getSaldo(), 2, ',', '.')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhum aluno cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>