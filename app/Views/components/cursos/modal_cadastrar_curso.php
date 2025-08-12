<div class="modal fade" id="modal-cadastrar-curso" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-curso-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-curso-label">Cadastrar Novo Curso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-cadastrar-curso" class="forms-sample" method="post" action="<?php echo base_url('sys/cursos/create'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
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