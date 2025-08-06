<div>
    <h1>Turmas</h1>
    <br>
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#turmaModal">
        Nova Turma
    </button>
    <br>
    <br>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Curso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($turmas)): ?>
                    <?php foreach($turmas as $turma) : ?>
                        <tr>
                            <td><?= esc($turma['id']) ?></td>
                            <td><?= esc($turma['nome']) ?></td>
                            <td><?= esc($turma['curso_nome'] ?? 'Sem Curso') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar" data-bs-toggle="modal" data-bs-target="#turmaModalEditar" data-turma-id="<?= esc($turma['id']) ?>" data-turma-nome="<?= esc($turma['nome']) ?>" data-turma-curso-id="<?= esc($turma['curso_id']) ?>">Editar</button>
                                <button class="btn btn-sm btn-danger btn-deletar" data-bs-toggle="modal" data-bs-target="#turmaModalDeletar" data-turma-id="<?= esc($turma['id']) ?>" data-turma-nome="<?= esc($turma['nome']) ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma turma encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <br>

    <?php if (session()->getFlashdata('successo')): ?>
        <div id="sucessoAlert" class="alert alert-fill-success" role="alert">
            <i class="mdi mdi-alert-circle"></i> <?= session()->getFlashdata('successo') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erro')): ?>
        <div id="erroAlert" class="alert alert-fill-danger" role="alert">
            <i class="mdi mdi-alert-circle"></i> <?= session()->getFlashdata('erro') ?>
        </div>
    <?php endif; ?>

</div>

<?= $this->include('components/turmas/modal_cad_turma', ['cursos' => $cursos]) ?>
<?= $this->include('components/turmas/modal_del_turma', ['cursos' => $cursos]) ?>
<?= $this->include('components/turmas/modal_edit_turma', ['cursos' => $cursos]) ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {

        //Para aparecer as divs de sucesso ou erro e depois sumir
        const alertaSucesso = $('#sucessoAlert');
        const alertaErro = $('#erroAlert');

        if (alertaSucesso.length) {
            setTimeout(function() {
                alertaSucesso.fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }

        if (alertaErro.length) {
            setTimeout(function() {
                alertaErro.fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
        }
        // ----------------------------------------

        // Aceita o submit da modal
        $('#turmaForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const url = form.attr('action');
            const data = form.serialize();

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#turmaModal').modal('hide'); // Fecha o modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                        }).then(() => {
                            location.reload(); // Recarrega a página para ver a nova turma
                        });
                    } else {
                        let errorMessages = '<ul>';
                        $.each(response.errors, function(key, value) {
                            errorMessages += '<li>' + value + '</li>';
                        });
                        errorMessages += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro de Validação',
                            html: errorMessages,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro de Servidor',
                        text: 'Não foi possível processar a requisição.',
                    });
                }
            });
        });

        // Limpa o formulário quando o modal é fechado
        $('#turmaModal').on('hidden.bs.modal', function() {
            $('#turmaForm')[0].reset();
        });

        //Coloca o valor das variaveis nome e id no modal de Deletar
        $('.btn-deletar').on('click', function() {
            var turmaId = $(this).data('turma-id');
            var turmaNome = $(this).data('turma-nome')

            var modal = $('#turmaModalDeletar')
            
            modal.find('#deleteTurmaId').val(turmaId)
            modal.find('#deletar-nome').text(turmaNome)
        })

        //Coloca o valor das variaveis nome, id e curso id no modal de Editar
        $('.btn-editar').on('click', function() {
            var turmaId = $(this).data('turma-id');
            var turmaNome = $(this).data('turma-nome');
            var cursoId = $(this).data('turma-curso-id');

            var modal = $('#turmaModalEditar');
            
            modal.find('#editTurmaId').val(turmaId);
            modal.find('#edit-nome').val(turmaNome);
            modal.find('#edit-curso_id').val(cursoId);
        })


    });
</script>