<div class="modal fade" id="modal-cadastrar-solicitacao" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-solicitacao-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-solicitacao-label">Cadastrar Nova Solicitação</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-cadastrar-solicitacao" class="forms-sample" method="post" action="<?php echo base_url('sys/solicitacoes/create'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="turma_id" class="form-label">Turma</label>
                        <select id="turma_id" name="turma_id" class="form-select py-2" required>
                            <option value="<?php echo null ?>">Selecione a turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo $turma['id'] ?>"><?= esc($turma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="data_refeicao" class="form-label">Data da refeição</label>
                        <input type="date" class="form-control" id="data_refeicao" name="data_refeicao" required>
                    </div>

                    <div class="mb-3 d-flex gap-3">
                        <div class="flex-fill">
                            <label for="crc" class="form-label">Código CRC</label>
                            <input type="text" class="form-control" id="crc" name="crc" placeholder="Digite o código CRC" required>
                        </div>
                        <div class="flex-fill">
                            <label for="codigo" class="form-label">Código Verificador</label>
                            <input type="number" class="form-control" id="codigo" name="codigo" placeholder="Digite o código verificador" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="justificativa" class="form-label">Justificativa</label>
                        <textarea
                            name="justificativa"
                            id="justificativa"
                            class="form-control"
                            rows="3"
                            minlength="8"
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