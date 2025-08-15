<div class="modal fade" id="modal-importar-alunos-turma" tabindex="-1" aria-labelledby="modal-importar-alunos-turma-label" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-importar-aluno-label">Importar Lista de Alunos<h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="form-importar-aluno" class="forms-sample" method="post" action="<?php echo base_url('sys/turmas/import'); ?>" enctype="multipart/form-data">
	            <?php echo csrf_field() ?>

                <input type="hidden" id="turma-id" name="id" />

                <div class="modal-body">
                    <div class="form-group">
                        <label id="label-importar-alunos">Selecionar planilha com alunos da Turma: <strong id="nome-turma"></strong> <strong id="nome-curso"></strong></label>
                        <input type="file" id="import--turma" name="planilha-alunos-turma" class="file-upload-default" required>
                        <div class="input-group col-xs-12 d-flex align-items-center">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Selecione a planilha clicando ao lado ->">
                            <span class="input-group-append ms-2">
                                <button class="file-upload-browse btn btn-primary" type="button">Buscar planilha</button>
                            </span>
                        </div>
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

        $("#modal-importar-alunos-turma").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);

            var id = button.data("id");
            var nomeTurma = button.data("nome");
            var nomeCurso = button.data("curso_nome"); 

            var modal = $(this);
            
            modal.find("#turma-id").val(id);

            modal.find("#nome-turma").text(nomeTurma);
            modal.find("#nome-curso").text(nomeCurso);
        });

    });
</script>