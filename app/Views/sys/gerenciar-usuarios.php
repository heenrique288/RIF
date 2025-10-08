<?php echo view('components/gerenciamento-usuarios/modal-cad-user.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-excluir-permanentemente.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-alterar-grupo.php'); ?>
<?php echo view('components/gerenciamento-usuarios/modal-atualizar-usuario.php'); ?>

<div class="mb-3">
    <h2 class="card-title mb-0">Gerenciamento de Usuários</h2>
</div>

<div class="row">
    <div class="col-md-2 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title mb-0">Ações</h5>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cad-user">
                        <i class="fa fa-plus-circle btn-icon-prepend"></i>
                        Adicionar Usuário
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="card-title mb-0">Filtros</h5>
                </div>
                </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <?php if (!empty($usuarios)): ?>
                        <table class="table mb-4" id="tabela-usuarios">
                            <thead>
                                <tr>
                                    <th><strong>Nome</strong></th>
                                    <th><strong>Email</strong></th>
                                    <th><strong>Grupo</strong></th>
                                    <th class="text-nowrap" style="text-align: center; width: 12%; min-width: 100px;"><strong>Ações</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum usuário encontrado no banco de dados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const usuariosData = <?= json_encode($usuarios ?? []) ?>;

    $(document).ready(function() {
        const initTooltips = () => {
            $('[data-bs-toggle="tooltip"]').each(function() {
                const tooltipInstance = bootstrap.Tooltip.getInstance(this);
                if (tooltipInstance) {
                    tooltipInstance.dispose();
                }
                new bootstrap.Tooltip(this, {
                    container: 'body',
                    customClass: 'tooltip-on-top',
                    offset: [0, 10]
                });
            });
        };

        if (usuariosData.length > 0) {
            $('#tabela-usuarios').DataTable({
                data: usuariosData,
                columns: [
                    { data: 'username' },
                    { data: 'email' },
                    { 
                        data: 'grupos',
                        render: function(data, type, row) {
                            if (data && data.length > 0) {
                                return data.join(', ');
                            }
                            return 'Nenhum grupo atribuído';
                        }
                    },
                    { 
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            const grupoAtual = (row.grupos && row.grupos.length > 0) ? row.grupos[0] : 'Nenhum';
                            return `
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados">
                                        <button type="button" class="btn btn-inverse-success button-trans-success btn-icon me-1 btn-editar-usuario d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#modal-atualizar-usuario"
                                            data-user-id="${row.id}" data-username="${row.username}" data-email="${row.email}">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                    </span>

                                    <span data-bs-toggle="tooltip" data-placement="top" title="Excluir usuário">
                                        <button type="button" class="btn btn-inverse-danger button-trans-danger btn-icon me-1 btn-excluir-permanentemente d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#modal-excluir-permanentemente"
                                            data-user-id="${row.id}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </span>

                                    <span data-bs-toggle="tooltip" data-placement="top" title="Alterar grupo">
                                        <button type="button" class="btn btn-inverse-info button-trans-info btn-icon me-1 btn-alterar-grupo d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#modal-alterar-grupo"
                                            data-user-id="${row.id}"
                                            data-grupo-atual="${grupoAtual}">
                                            <i class="fa fa-users"></i>
                                        </button>
                                    </span>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
                    search: "Pesquisar:",
                    url: "<?php echo base_url('assets/js/traducao-dataTable/pt_br.json'); ?>"
                },
                ordering: true,
                aLengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"],
                ],
                initComplete: function(settings, json) {
                    initTooltips();
                },
                drawCallback: function() {
                    initTooltips();
                }
            });
        }

        // Modal pra atualizar usuário
        $('#tabela-usuarios tbody').on('click', '.btn-editar-usuario', function() {
            var button = $(this);
            var userId = button.data('user-id');
            var username = button.data('username');
            var email = button.data('email');
            
            var modal = $('#modal-atualizar-usuario');
            modal.find('input[name="user_id"]').val(userId);
            modal.find('input[name="username"]').val(username);
            modal.find('input[name="email"]').val(email);
        });

        // Modal pra excluir usuário
        $('#tabela-usuarios tbody').on('click', '.btn-excluir-permanentemente', function() {
            var button = $(this);
            var userId = button.data('user-id');

            var modal = $('#modal-excluir-permanentemente');
            modal.find('#excluir-permanentemente-user-id').val(userId);
        });

        // Modal pra alterar grupo
        $('#tabela-usuarios tbody').on('click', '.btn-alterar-grupo', function() {
            var button = $(this);
            var userId = button.data('user-id');
            var grupoAtual = button.data('grupo-atual');
            
            var modal = $('#modal-alterar-grupo');
            modal.find('input[name="user_id"]').val(userId);
            modal.find('input[name="grupo_atual"]').val(grupoAtual);
        });

        <?php if (session()->has('error')): ?>
            <?php foreach (session('error') as $erro): ?>
                $.toast({
                    heading: 'Erro',
                    text: '<?= esc($erro); ?>',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loaderBg: '#dc3545',
                    position: 'top-center'
                });
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            $.toast({
                heading: 'Sucesso',
                text: '<?php echo session()->getFlashdata('success'); ?>',
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f96868',
                position: 'top-center'
            });
        <?php endif; ?>
    });
</script>