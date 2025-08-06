<div class="modal fade" id="turmaModal" tabindex="-1" aria-labelledby="turmaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="turmaModalLabel">Cadastrar Nova Turma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="turmaForm" action="<?= site_url('sys/turmas/criar') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Turma</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="curso_id" class="form-label">Curso</label>
                        <select class="form-select" id="curso_id" name="curso_id" required>
                            <option value="">Selecione um curso</option>
                            <?php if (!empty($cursos)): ?>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= esc($curso['id']) ?>"><?= esc($curso['nome']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="turmaForm">Salvar Turma</button>
            </div>
        </div>
    </div>
</div>
