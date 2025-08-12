<?= $this->include('components/turmas/modal_cadastrar_turma', ['cursos' => $cursos]) ?>

<div>
    <h1>Turmas</h1>

    <div class="my-4">
        <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-turma">
            <i class="fa fa-plus-circle btn-icon-prepend"></i>
            Nova Turma
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Curso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($turmas)): ?>
                    <?php foreach ($turmas as $turma) : ?>
                        <tr>
                            <td><?= esc($turma['id']) ?></td>
                            <td><?= esc($turma['nome']) ?></td>
                            <td><?= esc($turma['curso_nome'] ?? 'Sem Curso') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning">Editar</button>
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma turma encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
