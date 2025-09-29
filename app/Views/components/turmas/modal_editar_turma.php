<div class="modal fade" id="modal-editar-turma" tabindex="-1" role="dialog" aria-labelledby="modal-editar-turma-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-editar-turma-label">Editar Turma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="editarTurma" class="forms-sample" method="post" action="<?= base_url('sys/turmas/update') ?>">
                <?= csrf_field() ?>

                <div class="modal-body">
                    
                    <input type="hidden" name="id" id="editTurmaId" />

                    
                    <div class="form-group">
                        <label for="edit-nome">Nome</label>
                        <input type="text" class="form-control" minlength="3" required
                            id="edit-nome" name="nome" placeholder="Insira o nome da Turma">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-curso_id">Curso</label>
                        <select class="form-select" id="edit-curso_id" name="curso_id" required>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= esc($curso['id']) ?>"><?= esc($curso['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
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

<script>
    $(document).ready(function() {

        $("#modal-editar-turma").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var nome = button.data("nome");
            var id = button.data("id");
            var curso_id = button.data("curso_id");

            var modal = $(this);
            modal.find("#editTurmaId").val(id);
            modal.find("#edit-nome").val(nome);
            modal.find("#edit-curso_id").val(curso_id);
        });

    });
</script>