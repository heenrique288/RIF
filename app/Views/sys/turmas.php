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
                                <div class="d-flex">
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Editar">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-editar-turmas"
                                            data-id="<?php echo esc($turma['id']); ?>"
                                            data-nome="<?php echo esc($turma['nome']); ?>"
                                            data-curso_id="<?php echo esc($turma['curso_id']); ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </span>

                                    <span data-bs-toggle="tooltip" data-placement="top" title="Excluir">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-deletar-turma"
                                            data-id="<?php echo esc($turma['id']); ?>"
                                            data-nome="<?php echo esc($turma['nome']); ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </span>
                                </div>
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
