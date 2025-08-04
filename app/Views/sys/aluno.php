<!DOCTYPE html>
<html lang="pt-br">
<div>
    <body>
        <div class="container">
            <h1>Alunos Cadastrados</h1>
            <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#alunoModal">
            Novo Aluno
            </button>
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
</div>
<?= $this->include('components/alunos/modal_cad_aluno', ['aluno' => $alunos]) ?>
</html>
