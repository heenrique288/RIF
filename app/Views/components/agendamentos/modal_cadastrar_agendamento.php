<div class="modal fade" id="modal-cadastrar-agendamento" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-agendamento-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-agendamento-label">Cadastrar Novo Agendamento</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-cadastrar-agendamento" class="forms-sample" method="post" action="<?php echo base_url('sys/agendamento/admin/create'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="turma_id" class="form-label">Turma</label>
                        <select id="turma_id" name="turma_id" class="form-select py-2" required>
                            <option value="">Selecione a turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo $turma['id'] ?>"><?= esc($turma['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Aluno(s)</label>
                        <div id="alunos-container">
                            </div>

                        <div id="alunos-selecionados" class="mt-2">
                            <label>Selecionados:</label>
                            <ul id="lista-alunos" class="list-group">
                                </ul>
                        </div>
                    </div>

                    <input type="hidden" name="matriculas[]" id="matriculas-hidden">

                    <div class="mb-3">
                        <label class="form-label">Data(s) do Agendamento</label>
                        <div id="inline-datepicker"></div>
                        <input type="hidden" name="datas[]" id="datas-hidden">
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
                            required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>