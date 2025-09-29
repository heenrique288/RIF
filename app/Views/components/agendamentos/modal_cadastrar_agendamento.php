<div class="modal fade" id="modal-cadastrar-agendamento" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-agendamento-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-agendamento-label">Cadastrar Novo Agendamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-cadastrar-agendamento" class="forms-sample" method="post" action="<?php echo base_url('sys/agendamento/admin/create'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="turma_id" class="form-label">Adicionar Alunos da Turma</label>
                        <select id="turma_id" name="turma_id" class="form-select">
                            <option value="">Selecione a turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?= $turma['id'] ?>">
                                    <?= esc($turma['nome_turma'] . ' - ' . esc($turma['nome_curso'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="alunos-container" class="d-flex flex-wrap gap-1 mb-3"></div>
                    
                    <h6>Alunos Selecionados</h6>
                    <ul id="lista-alunos" class="list-unstyled mb-3 p-2 rounded" style="background-color: #2a3038; max-height: 150px; overflow-y: auto; min-height: 50px;">
                        <li class="text-muted small p-2">Nenhum aluno selecionado.</li>
                    </ul>
                    <input type="hidden" name="matriculas" id="matriculas-hidden">

                    <div class="mb-3">
                        <label class="form-label">Data(s) do Agendamento</label>
                        <div id="datepicker-container-cadastro"></div> 
                        <input type="hidden" name="datas" id="datas-hidden">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select py-2" id="status" name="status" required>
                                <option value="" selected disabled>Selecione o Status</option>
                                <option value="0">Disponível</option>
                                <option value="1">Confirmada</option>
                                <option value="2">Retirada</option>
                                <option value="3">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-select" id="motivo" name="motivo" required>
                                <option value="" selected disabled>Selecione o motivo</option>
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
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>