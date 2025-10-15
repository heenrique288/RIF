<div class="modal fade" id="modal-confirmar-senha" tabindex="-1" aria-labelledby="modal-confirmar-senha-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-confirmar-senha-label">Confirmação de Segurança</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>

            <form id="formConfirmarSenha" method="post" action="<?= base_url('sys/turmas/delete') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="senhaTurmaId">

                <div class="modal-body">
                    <p>Digite sua senha para confirmar a exclusão permanente da turma.</p>
                    <input type="password" name="senha" class="form-control" placeholder="Sua senha" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Permanentemente</button>
                </div>
            </form>

        </div>
    </div>
</div>
