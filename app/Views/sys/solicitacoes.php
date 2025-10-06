<?php echo view('components/solicitacoes/modal_cadastrar_solicitacao', ["turmas" => $turmas]) ?>
<?php echo view('components/solicitacoes/modal_editar_solicitacao', ["turmas" => $turmas]) ?>
<?php echo view('components/solicitacoes/modal_deletar_solicitacao') ?>


<div class="mb-3">
    <h2 class="card-title mb-0">Solicitações de Refeições</h2>
</div>
<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card ">
            <div class="card-body">
                <div class="my-1">
                    <div class="mb-3">
                        <h5 class="card-title mb-0">Ações</h5>
                    </div>
                    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-solicitacao">
                        <i class="fa fa-plus-circle btn-icon-prepend"></i>
                        Nova Solicitação
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card ">
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
                <?php if (!empty($solicitacoes)): ?>
                    <table class="table mb-4" id="listagem-solicitacoes" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Status</th>
                                <th>Turma</th>
                                <th>Data</th>
                                <th>CRC</th>
                                <th>Código</th>
                                <th style="text-align: center; width: 10%; min-width: 100px;">Ações</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhum aluno encontrado no banco de dados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script>
    const solicitacoesData = <?= json_encode($solicitacoes ?? []) ?>;
    $(document).ready(function() {
        const initTooltips = () => {
            $('[data-bs-toggle="tooltip"]').each(function() {
                const tooltipInstance = bootstrap.Tooltip.getInstance(this);
                if (tooltipInstance) {
                    tooltipInstance.dispose();
                }
                new bootstrap.Tooltip(this);
            });
        };
        if (solicitacoesData.length > 0) {
            $('#listagem-solicitacoes').DataTable({
                data: solicitacoesData,
                columns: [
                    { data: 'id' },
                    { data: 'status' },
                    { data: 'turma_id' },
                    { data: 'data_refeicao' },
                    { data: 'crc' },
                    { data: 'codigo' },
                    {
                        data: null, // Coluna de ações não vem do banco
                        orderable: false, // Não permite ordenar por esta coluna
                        searchable: false, // Não permite buscar por esta coluna
                        render: function(data, type, row) {
                            // 'row' contém todos os dados da linha atual
                            return `
                                <div class="d-flex align-center justify-content-center gap-2">
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar solicitação">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-success btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-editar-solicitacao"
                                            data-id="${row.id}"
                                            data-status="${row.status}"
                                            data-turma_id="${row.turma_id}"
                                            data-data_refeicao="${row.data_refeicao}"
                                            data-crc="${row.crc}"
                                            data-codigo="${row.codigo}"
                                            data-justificativa="${row.justificativa}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </span>
                                    <span data-bs-toggle="tooltip" data-placement="top" title="Excluir solicitação">
                                        <button
                                            type="button"
                                            class="justify-content-center align-items-center d-flex btn btn-inverse-danger btn-icon me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-deletar-solicitacao"
                                            data-id="${row.id}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </span>
                                </div>
                            `;
                        }
                    }
                ],
                // Configurações adicionais (tradução, paginação, etc.)
                language: {
                    search: "Pesquisar:",
                    url: "<?= base_url('assets/js/traducao-dataTable/pt_br.json'); ?>"
                },
                ordering: true,
                aLengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"],
                ],
                // Funções para garantir que os tooltips funcionem após a tabela ser desenhada
                initComplete: function() {
                    initTooltips();
                },
                drawCallback: function() {
                    initTooltips();
                }
            });
        }
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