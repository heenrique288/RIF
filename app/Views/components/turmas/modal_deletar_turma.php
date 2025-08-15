<div class="modal fade" id="modal-deletar-turma" tabindex="-1" aria-labelledby="modal-deletar-turma-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletar-turma-label">Confirmação de Exclusão</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="turmaFormDeletar" method="post" action="<?= base_url('sys/turmas/deletar') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="deleteTurmaId">
                    <p class="text-break">Confirma a exclusão da turma <b id='deletar-nome'></b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Turma</button>
                </div>
            </form>
        </div>
    </div>
</div>