<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; }
        h1 { text-align: center; }
        ul { list-style-type: none; padding: 0; }
        li { background-color: #f0f0f0; margin-bottom: 5px; padding: 10px; border-radius: 5px; }
        li span { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Alunos Cadastrados</h1>
        <?php if (isset($alunos) && !empty($alunos)): ?>
            <ul>
                <?php foreach ($alunos as $aluno): ?>
                    <li>
                        <span>Matrícula:</span> <?= esc($aluno['matricula']) ?><br>
                        <span>Nome:</span> <?= esc($aluno['nome']) ?><br>
                        <span>Turma ID:</span> <?= esc($aluno['turma_id']) ?><br>
                        <span>Status:</span> <?= esc($aluno['status']) ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum aluno encontrado no banco de dados.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
