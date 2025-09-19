<div class="modal fade" id="modal-editar-agendamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Agendamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            
            <form id="form-editar-agendamento" action="<?= site_url('sys/agendamento/admin/update') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="original_aluno_ids" id="edit_original_aluno_ids">
                    <input type="hidden" name="original_datas" id="edit_original_datas">
                    <input type="hidden" name="original_motivo" id="edit_original_motivo">

                    <div class="mb-3">
                        <label for="edit_turma_id" class="form-label">Adicionar Alunos da Turma</label>
                        <select class="form-select" id="edit_turma_id">
                            <option value="">Selecione uma turma...</option>
                            <?php if (!empty($turmas)): ?>
                                <?php foreach ($turmas as $turma): ?>
                                    <option value="<?= $turma['id'] ?>"><?= esc($turma['nome_turma'] . ' - ' . $turma['nome_curso']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div id="edit_alunos-container" class="d-flex flex-wrap gap-1 mb-3"></div>

                    <h6>Alunos Selecionados</h6>
                    <ul id="edit_lista-alunos" class="list-unstyled mb-3 p-2 rounded" style="background-color: #2a3038; max-height: 150px; overflow-y: auto; min-height: 50px;">
                    </ul>
                    <input type="hidden" name="matriculas[]" id="edit_matriculas-hidden">

                    <div class="mb-3">
                        <label class="form-label">Data(s) do Agendamento</label>
                        <div id="edit-datepicker"></div>
                        <input type="hidden" name="datas[]" id="edit_datas-hidden">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="" disabled>Selecione o Status</option>
                                <option value="0">Disponível</option>
                                <option value="1">Confirmada</option>
                                <option value="2">Retirada</option>
                                <option value="3">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_motivo" class="form-label">Motivo</label>
                            <select class="form-select" name="motivo" id="edit_motivo" required>
                                <option value="" disabled>Selecione o motivo</option>
                                <option value="0">Contraturno</option>
                                <option value="1">Estágio</option>
                                <option value="2">Treino</option>
                                <option value="3">Projeto</option>
                                <option value="4">Visita Técnica</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>