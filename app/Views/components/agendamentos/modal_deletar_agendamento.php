<div class="modal fade" id="modal-deletar-agendamento" tabindex="-1" aria-labelledby="modal-deletar-agendamento-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletar-agendamento-label">Confirmação de Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="<?= base_url('sys/agendamento/admin/delete') ?>">
                <?= csrf_field() ?>
                
                <input type="hidden" name="delete_info" id="deleteAgendamentoInfo">

                <div class="modal-body">
                    <p class="text-break">
                        Confirma a exclusão do agendamento para <strong id="deleteAgendamentoNome"></strong>?
                    </p>
                    <small>Atenção: Todos os alunos e datas vinculados a este agendamento serão removidos.</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Agendamento</button>
                </div>
            </form>

        </div>
    </div>
</div>