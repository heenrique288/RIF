<div class="modal fade" id="modal-deletar-solicitacao" tabindex="-1" role="dialog" aria-labelledby="modal-deletar-solicitacao-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletar-solicitacao-label">Deletar Solicitação</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-deletar-solicitacao" class="forms-sample" method="post" action="<?php echo base_url('sys/solicitacoes/delete'); ?>">
                <?php echo csrf_field() ?>

                <input type="hidden" id="deletar-id" name="id" />

                <div class="modal-body text-break">Confirma a exclusão da solicitação?</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger me-2">Excluir</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#modal-deletar-solicitacao").on("show.bs.modal", function(event) {
            var form = $(event.relatedTarget);

            var id = form.data("id");

            var modal = $(this);
            modal.find("#deletar-id").val(id);
        });

    });
</script>