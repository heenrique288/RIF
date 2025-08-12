<div class="modal fade" id="modal-deletar-curso" tabindex="-1" role="dialog" aria-labelledby="modal-deletar-curso-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletar-curso-label">Confirmação necessária</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-deletar-curso" class="forms-sample" method="post" action="<?php echo base_url("sys/cursos/delete"); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body text-break">

                    <input type="hidden" id="deletar-id" name="id" />

                    <p>Confirma a exclusão do curso <strong id="deletar-nome"></strong>?</p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#modal-deletar-curso").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var id = button.data("id");
            var nome = button.data("nome");

            var modal = $(this);
            modal.find("#deletar-id").val(id);
            modal.find("#deletar-nome").text(nome);
        });

    });
</script>