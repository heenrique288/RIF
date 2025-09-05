<?php echo view('components/agendamentos/modal_cadastrar_agendamento', ["turmas" => $turmas], ["alunos" => $alunos]) ?>

<h1>Agendamento de Refeição</h1>
<div class="my-4">
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-agendamento">
        <i class="fa fa-plus-circle btn-icon-prepend"></i>
        Novo Agendamento
    </button>
</div>

<?php if (isset($agendamentos) && !empty($agendamentos)): ?>
<table class="table mb-4" id="listagem-agendamentos">
    <thead>
        <tr>
            <th>Turma ou Aluno</th>
            <th>Data</th>
            <th>CRC</th>
            <th>Código</th>
            <th>Justificativa</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<?php else: ?>
    <p>Nenhum agendamento encontrado no banco de dados.</p>
<?php endif; ?>

<div class="modal fade" id="modal-ver-alunos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alunos do Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <ul id="lista-alunos-modal" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ver-justificativa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Justificativa do Agendamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p id="texto-justificativa"></p>
            </div>
        </div>
    </div>
</div>

<style>
    #lista-alunos-modal li {
        background-color: #2a3038;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>

<script>
    const dataTableLangUrl = "<?php echo base_url('assets/js/traducao-dataTable/pt_br.json'); ?>";
    const agendamentosData = <?= json_encode($agendamentos ?? []) ?>;

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

        if (agendamentosData.length > 0) {
            $('#listagem-agendamentos').DataTable({
                data: agendamentosData,
                columns: [{
                    data: 'turma_aluno',
                    render: function(data, type, row) {
                        if (row.alunos && row.alunos.length > 1) {
                            return `${row.alunos[0]} <a href="#" class="ver-mais-alunos" data-alunos='${JSON.stringify(row.alunos)}'> + ${row.alunos.length - 1} alunos</a>`;
                        } else if (row.alunos && row.alunos.length === 1) {
                            return row.alunos[0];
                        }
                        return data;
                    }
                }, {
                    data: 'data'
                }, {
                    data: 'crc'
                }, {
                    data: 'codigo'
                }, {
                    data: 'justificativa',
                    render: function(data, type, row) {
                        return `<a href="#" class="ver-justificativa" data-justificativa='${data.replace(/'/g, "\\'")}'>Ver justificativa</a>`;
                    }
                }, {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex">
                                <span data-bs-toggle="tooltip" data-placement="top" title="Editar agendamento">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </span>
                                <span data-bs-toggle="tooltip" data-placement="top" title="Excluir agendamento">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </span>
                            </div>
                        `;
                    }
                }],
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
    });

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
</script>