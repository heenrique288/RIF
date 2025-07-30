<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Formulário de Aluno</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Aluno</h1>
        <form action="index.php?action=save" method="post">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control" id="nome" placeholder="Digite o nome" value="<?= htmlspecialchars($aluno->getNome() ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula</label>
                <input type="text" name="matricula" class="form-control" id="matricula" placeholder="Digite a matrícula" value="<?= htmlspecialchars($aluno->getMatricula() ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Digite o email" value="<?= htmlspecialchars($aluno->getEmail() ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="saldo">Saldo Inicial</label>
                <input type="number" step="0.01" name="saldo" class="form-control" id="saldo" placeholder="0.00" value="<?= htmlspecialchars($aluno->getSaldo() ?? '0.00') ?>">
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
</body>
</html>