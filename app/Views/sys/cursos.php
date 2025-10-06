<?php echo view('components/cursos/modal_cadastrar_curso') ?>
<?php echo view('components/cursos/modal_editar_curso') ?>
<?php echo view('components/cursos/modal_deletar_curso') ?>

<div class="mb-3">
    <h2 class="card-title mb-0">Cursos</h2>
</div>
<div class="row">
    <div class="col-md-2 grid-margin stretch-card">
        <div class="card ">
            <div class="card-body">
                <div>
                    <button type="button" class="btn btn-primary btn-fw " data-bs-toggle="modal" data-bs-target="#modal-cadastrar-curso">
                        <i class="fa fa-plus-circle btn-icon-prepend"></i>
                        Novo Curso
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- =-=-=-=-= SE PRECISAR DE FILTROS NESSA TELA, DESCOMENTAR ESSA DIV =-=-=-=-= -->
    <!-- <div class="col-md-10 grid-margin stretch-card">
        <div class="card ">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">Filtros</h4>
                </div>
            </div>
        </div>
    </div> -->
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <?php if (isset($cursos) && !empty($cursos)): ?>
                <table class="table mb-4" id="listagem-cursos">
                    <thead>
                        <tr>
                            <th style="width: 3%; min-width: 50px;"><strong>Código</strong></th>
                            <th><strong>Nome</strong></th>
                            <th style="text-align: center; width: 10%; min-width: 100px;"><strong>Ações</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
                <?php else: ?>
                    <p>Nenhum curso encontrado no banco de dados.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
    const dataTableLangUrl = "<?php echo base_url('assets/js/traducao-dataTable/pt_br.json'); ?>";
    const cursosData = <?= json_encode($cursos ?? []) ?>;

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

        if (cursosData.length > 0) {
            $('#listagem-cursos').DataTable({
                data: cursosData,
                columns: [
                    { data: 'id' },
                    { data: 'nome' },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex align-center justify-content-center gap-2">
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados do curso">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-editar-curso"
                                            data-id="${data.id}"
                                            data-nome="${data.nome}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </span>
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Excluir curso">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-deletar-curso"
                                            data-id="${data.id}"
                                            data-nome="${data.nome}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </span>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
                    search: "Pesquisar:",
                    url: dataTableLangUrl
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
    
        // Lógica de notificação
        <?php if (session()->has('erros')): ?>
            <?php foreach (session('erros') as $erro): ?>
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

        <?php if (!session()->has('erros') && session()->has('sucesso')): ?>
            $.toast({
                heading: 'Sucesso',
                text: '<?= session('sucesso') ?>',
                showHideTransition: 'fade',
                icon: 'success',
                loaderBg: '#35dc5fff',
                position: 'top-center'
            });
        <?php endif; ?>
    });
</script>