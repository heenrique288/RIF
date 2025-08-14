<div class="modal fade" id="modal-editar-solicitacao" tabindex="-1" role="dialog" aria-labelledby="modal-editar-solicitacao-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-editar-solicitacao-label">Editar Solicitação</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-editar-solicitacao" class="forms-sample" method="post" action="<?php echo base_url('sys/solicitacoes/update'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">

                    <input type="hidden" id="edit-id" name="id" />
                    <input type="hidden" id="edit-status" name="status" />

                    <div class="mb-3">
                        <label for="edit-turma_id" class="form-label">Turma</label>
                        <select id="edit-turma_id" name="turma_id" class="form-select py-2" required>
                            <option value="<?php echo null ?>">Selecione a turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo $turma['id'] ?>"><?= esc($turma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-data_refeicao" class="form-label">Data da refeição</label>
                        <input type="date" class="form-control" id="edit-data_refeicao" name="data_refeicao" required>
                    </div>

                    <div class="mb-3 d-flex gap-3">
                        <div class="flex-fill">
                            <label for="edit-crc" class="form-label">Código CRC</label>
                            <input type="text" class="form-control" id="edit-crc" name="crc" placeholder="Digite o código CRC" required>
                        </div>
                        <div class="flex-fill">
                            <label for="edit-codigo" class="form-label">Código Verificador</label>
                            <input type="number" class="form-control" id="edit-codigo" name="codigo" placeholder="Digite o código verificador" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit-justificativa" class="form-label">Justificativa</label>
                        <textarea
                            name="justificativa"
                            id="edit-justificativa"
                            class="form-control"
                            rows="3"
                            maxlength="255"
                            style="min-height: 80px;"
                            require></textarea>
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

        $("#modal-editar-solicitacao").on("show.bs.modal", function(event) {
            var form = $(event.relatedTarget);

            var id = form.data("id");
            var status = form.data("status");
            var turma_id = form.data("turma_id");
            var data_refeicao = form.data("data_refeicao");
            var crc = form.data("crc");
            var codigo = form.data("codigo");
            var justificativa = form.data("justificativa");

            var modal = $(this);
            modal.find("#edit-id").val(id);
            modal.find("#edit-status").val(status);
            modal.find("#edit-turma_id").val(turma_id);
            modal.find("#edit-data_refeicao").val(data_refeicao);
            modal.find("#edit-crc").val(crc);
            modal.find("#edit-codigo").val(codigo);
            modal.find("#edit-justificativa").val(justificativa);
        });

    });
</script>