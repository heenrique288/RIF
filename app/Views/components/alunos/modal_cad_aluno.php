<div class="modal fade" id="alunoModal" tabindex="-1" aria-labelledby="alunoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alunoModalLabel">Cadastrar Novo Aluno</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="alunoForm" action="<?= site_url('sys/alunos/criar') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Aluno</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: João da Silva" required>
                    </div>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">Matrícula</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Ex: 20230001" required>
                    </div>
                    <div class="mb-3">
                        <label for="turma_id" class="form-label">Turma</label>
                        <select class="form-control" id="turma_id" name="turma_id" required>
                            <option value="">Selecione uma turma</option>
                            <?php if (!empty($turmas)): ?>
                                <?php foreach ($turmas as $turma): ?>
                                    <option value="<?= $turma['id'] ?>" data-curso-id="<?= $turma['curso_id'] ?>" data-curso-nome="<?= $turma['curso_nome'] ?>">
                                        <?= esc($turma['nome']) ?> - <?= esc($turma['curso_nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="curso" class="form-label">Curso</label>
                        <input type="text" class="form-control" id="curso" name="curso" disabled placeholder="Selecione uma turma">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                    <div class="card-body">
                        <label for="emails" class="form-label">Email</label>
                        <div id="email-repeater-container">
                            <div class="email-repeater-item d-flex align-items-center mb-2">
                                <div class="input-group me-2">
                                    <input type="email" class="form-control form-control-sm" name="email[]" placeholder="aluno@gmail.com" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm icon-btn remove-email me-2">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                                <button type="button" class="btn btn-info btn-sm icon-btn add-email">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="limparFormulario()">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="alunoForm">Salvar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-dialog {
        margin-top: 10vh;
    }

    .form-control[disabled], .form-control[readonly] {
        background-color: #2a3038;
        color: #fff;
        opacity: 1;
    }

    .form-control {
        color: #fff !important;
    }

    select.form-control {
        cursor: pointer;
    }

    input#curso.form-control[disabled] {
        cursor: not-allowed;
    }
</style>


<script src="<?= base_url('assets/vendors/jquery.repeater/jquery.repeater.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        // Lógica para o seletor de turma
        $('#turma_id').on('change', function() {
            const cursoNome = $(this).find('option:selected').data('curso-nome');
            $('#curso').val(cursoNome || 'Selecione uma turma');
        });

        // --- Lógica do Repeater (função de adicionar e remover email) Otimizada ---

        const repeaterContainer = $('#email-repeater-container');
        const firstItem = repeaterContainer.find('.email-repeater-item').first();
        
        function updateEmailButtons() {
            const isSingleItem = repeaterContainer.find('.email-repeater-item').length <= 1;
            repeaterContainer.find('.remove-email').toggle(!isSingleItem);
        }

        repeaterContainer.on('click', '.add-email', function() {
            const newItem = firstItem.clone();
            newItem.find('input').val('');
            repeaterContainer.append(newItem);
            updateEmailButtons();
        });

        repeaterContainer.on('click', '.remove-email', function() {
            if (confirm('Tem certeza que deseja remover este e-mail?')) {
                $(this).closest('.email-repeater-item').remove();
                updateEmailButtons();
            }
        });

        $('#alunoModal').on('hidden.bs.modal', function () {
            repeaterContainer.find('.email-repeater-item').not(':first').remove();
            firstItem.find('input').val('');
            document.getElementById("alunoForm").reset();
            updateEmailButtons();
        });

        updateEmailButtons();
    });
</script>