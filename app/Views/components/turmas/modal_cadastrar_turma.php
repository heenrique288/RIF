<div class="modal fade" id="modal-cadastrar-turma" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-turma-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-turma-label">Cadastrar Nova Turma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-cadastrar-turma" method="post" action="<?= base_url('sys/turmas/create') ?>">
                <?php csrf_field() ?>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Turma</label>
                        <input type="text" class="form-control" id="nome" name="nome" minlength="3" required>
                    </div>

                    <div class="mb-3">
                        <label for="curso_id" class="form-label">Curso</label>
                        <select class="form-select py-2" id="curso_id" name="curso_id" required>
                            <option value="">Selecione um curso</option>
                            <?php if (!empty($cursos)): ?>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= esc($curso['id']) ?>"><?= esc($curso['nome']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                </div>
            </form>

        </div>
    </div>
</div>