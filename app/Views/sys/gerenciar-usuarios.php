<?php echo view('components/gerenciamento-usuarios/modal-cad-user.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-excluir-permanentemente.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-alterar-grupo.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-atualizar-usuario.php'); ?>

<!-- mostrar ALERT em caso de erro -->
<?php if (session()->has('error')): ?>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('error') as $erro): ?>
                                <li> <i class="mdi mdi-alert-circle"></i><?php echo esc($erro); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<h1>Gerenciamento de Usuário</h1>
<div class="my-4">
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cad-user">
        <i class="fa fa-plus-circle btn-icon-prepend"></i>
        Adicionar Usuário
    </button>
</div>

<!-- início da tabela -->
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-4" id="listagem-usuarios">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Grupo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo esc($usuario->username); ?></td>
                                        <td><?php echo esc($usuario->email); ?></td>
                                        <td>
                                            <?php if (!empty($usuario->grupos)): ?>
                                                <?php echo esc(implode(', ', $usuario->grupos)); ?>
                                            <?php else: ?>
                                                Nenhum grupo atribuído
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <!-- Botão Editar -->
                                                <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados do usuário">
                                                    <button type="button" class="btn button-trans-success btn-icon me-1 btn-editar-usuario d-flex align-items-center justify-content-center"
                                                        data-bs-toggle="modal" data-bs-target="#modal-atualizar-usuario"
                                                        data-user-id="<?php echo $usuario->id; ?>" data-username="<?php echo esc($usuario->username); ?>"
                                                        data-email="<?php echo esc($usuario->email); ?>">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </span>

                                                <!-- Botão Excluir -->
                                                <span data-bs-toggle="tooltip" data-placement="top" title="Excluir usuário">
                                                    <button type="button" class="btn button-trans-danger btn-icon me-1 btn-excluir-permanentemente d-flex align-items-center justify-content-center"
                                                        data-user-id="<?php echo $usuario->id; ?>" data-bs-toggle="modal" data-bs-target="#modal-excluir-permanentemente">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </span>

                                                <!-- Botão Alterar Grupo -->
                                                <span data-bs-toggle="tooltip" data-placement="top" title="Alterar grupo">
                                                    <button type="button" class="btn button-trans-info btn-icon me-1 d-flex align-items-center justify-content-center"
                                                        data-bs-toggle="modal" data-bs-target="#modal-alterar-grupo"
                                                        data-user-id="<?php echo $usuario->id; ?>"
                                                        data-grupo-atual="<?php echo !empty($usuario->grupos) ? esc($usuario->grupos[0]) : 'Nenhum'; ?>">
                                                        <i class="fa fa-users"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const excluirBtns = document.querySelectorAll(".btn-excluir-permanentemente");
        const inputUserId = document.getElementById("excluir-permanentemente-user-id");

        excluirBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const userId = this.getAttribute("data-user-id");
                inputUserId.value = userId;
            });
        });
    });

    $(document).ready(function() {
        // Passa o ID e o Grupo Atual do usuário para o modal de alteração de grupo
        $('#modal-alterar-grupo').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botão que acionou o modal
            var userId = button.data('user-id'); // Captura o ID do usuário
            var grupoAtual = button.data('grupo-atual'); // Captura o grupo atual

            // Preenche os campos no modal
            $(this).find('input[name="user_id"]').val(userId);
            $(this).find('input[name="grupo_atual"]').val(grupoAtual);
        });

        // Ativa os tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Exibe mensagem de sucesso se o flashdata estiver com 'sucesso'
        <?php if (session()->getFlashdata('success')) : ?>
            $.toast({
                heading: 'Sucesso',
                text: '<?php echo session()->getFlashdata('success'); ?>',
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'top-center'
            });
        <?php endif; ?>

        // Passa o ID, username e email do usuário para o modal de atualização de usuário
        $('#modal-atualizar-usuario').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botão que acionou o modal
            var userId = button.data('user-id');
            var username = button.data('username');
            var email = button.data('email');

            // Define os valores no modal
            $(this).find('input[name="user_id"]').val(userId);
            $(this).find('input[name="username"]').val(username);
            $(this).find('input[name="email"]').val(email);
        });
    });
</script>
