<div class="modal fade" id="modal-cadastrar-curso" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-curso-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-curso-label">Cadastrar Novo Curso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            
            <form id="form-cadastrar-curso" class="forms-sample" method="post" action="<?php echo base_url('sys/cursos/criar'); ?>">
                <div class="modal-body">
                    <?php echo csrf_field() ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Curso</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
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

        // Aceita o submit da modal
        // $('#form-cadastrar-curso').on('submit', function(e) {
        //     e.preventDefault();

        // });


        // const form = $(this);
        // const url = form.attr('action');
        // const data = form.serialize();

        // const res = await fetch(url);

        // console.log(res);



        // Limpa o formulário quando o modal é fechado
        // $('#form-cadastrar-curso').on('hidden.bs.modal', function() {
        //     $('#form-cadastrar-curso')[0].reset();
        // });
    });
</script>