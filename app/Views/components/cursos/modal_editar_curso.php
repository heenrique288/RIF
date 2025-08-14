<div class="modal fade" id="modal-editar-curso" tabindex="-1" role="dialog" aria-labelledby="modal-editar-curso-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-editar-curso-label">Editar Curso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-editar-curso" class="forms-sample" method="post" action="<?php echo base_url("sys/cursos/update"); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">

                    <input type="hidden" id="edit-id" name="id" />

                    <div class="form-group">
                        <label for="edit-nome">Nome</label>
                        <input type="text" class="form-control" required
                            id="edit-nome" name="nome" placeholder="Digite o nome do curso">
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

<script>
    $(document).ready(function() {

        $("#modal-editar-curso").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var nome = button.data("nome");
            var id = button.data("id");

            var modal = $(this);
            modal.find("#edit-id").val(id);
            modal.find("#edit-nome").val(nome);
        });

    });
</script>