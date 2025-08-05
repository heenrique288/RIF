<div class="modal fade" id="cursoModal" tabindex="-1" aria-labelledby="cursoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cursoModalLabel">Cadastrar Novo Curso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cursoForm" action="<?= site_url('cursos/criar') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="turmaForm">Salvar Curso</button>
            </div>
        </div>
    </div>
</div>
