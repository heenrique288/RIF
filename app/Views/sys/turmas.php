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
                                <button class="btn btn-sm btn-warning">Editar</button>
                                <button class="btn btn-sm btn-danger">Excluir</button>
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
</div>

<?= $this->include('components/turmas/modal_cad_turma', ['cursos' => $cursos]) ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
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
    });
</script>