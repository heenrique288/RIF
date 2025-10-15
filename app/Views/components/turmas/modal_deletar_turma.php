<div class="modal fade" id="modal-deletar-turma" tabindex="-1" aria-labelledby="modal-deletar-turma-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletar-turma-label">Confirmação de Exclusão</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>

            <form id="turmaFormDeletar" method="post" action="<?= base_url('sys/turmas/delete') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="deleteTurmaId">

                <div class="modal-body" id="deleteModalBody">
                    <p class="text-break">
                        Confirma a exclusão da turma <strong id="deletar-nome"></strong>?
                    </p>
                </div>

                <div class="modal-footer" id="deleteModalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="btnExcluirTurma">Excluir Turma</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#modal-deletar-turma").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var id = button.data("id");
            var nome = button.data("nome");

            var modal = $(this);
            modal.find("#deleteTurmaId").val(id);
            modal.find("#deletar-nome").text(nome);
        });

    });
</script>