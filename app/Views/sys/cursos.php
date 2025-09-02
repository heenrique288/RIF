<?php echo view('components/cursos/modal_cadastrar_curso') ?>
<?php echo view('components/cursos/modal_editar_curso') ?>
<?php echo view('components/cursos/modal_deletar_curso') ?>

<div>
    <h1>Cursos</h1>

    <div class="my-4">
        <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-curso">
            <i class="fa fa-plus-circle btn-icon-prepend"></i>
            Novo Curso
        </button>
    </div>

    <?php if (isset($cursos) && !empty($cursos)): ?>
    <table class="table mb-4" id="listagem-cursos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>
    <?php else: ?>
        <p>Nenhum curso encontrado no banco de dados.</p>
    <?php endif; ?>

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
                                <div class="d-flex">
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