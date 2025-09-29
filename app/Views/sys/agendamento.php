<?php echo view('components/agendamentos/modal_cadastrar_agendamento', ['turmas' => $turmas, 'alunos' => $alunos]) ?>
<?php echo view('components/agendamentos/modal_editar_agendamento', ["turmas" => $turmas]); ?>
<?php echo view('components/agendamentos/modal_deletar_agendamento');?>

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
            <th><strong>Turma ou Aluno</strong></th>
            <th><strong>Data</strong></th>
            <th><strong>Status</strong></th>
            <th><strong>Motivo</strong></th>
            <th style="text-align: center; width: 10%; min-width: 100px;"><strong>Ações</strong></th>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <ul id="lista-alunos-modal" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

<style>
    #lista-alunos li {
        background-color: #2a3038;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }

    #lista-alunos li .remove-aluno {
        cursor: pointer;
        color: #dc3545;
        font-weight: bold;
        margin-left: 1rem;
    }

    #lista-alunos li .remove-aluno:hover {
        color: #a71d2a;
    }

    #lista-alunos-modal .list-group-item {
        background-color: #2a3038;
        color: #ffffff;
        border-color: #444;
        border-width: 0 0 1px 0;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    #lista-alunos-modal .list-group-item:first-child {
        border-top-width: 1px;
    }

    #lista-alunos-modal .turma-header {
        background-color: #212529;
        font-weight: bold;
        font-size: 1.05em;
    }

    #lista-alunos-modal .aluno-item {
        padding-left: 2rem;
    }

    #lista-alunos-modal .list-group-item:last-child {
        border-bottom-width: 0;
    }
    #lista-alunos li, #edit_lista-alunos li {
        background-color: #2a3038;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }

    #listagem-agendamentos td:last-child {
        width: 1%;
        white-space: nowrap;
        padding: 8px 12px !important;
    }
    .flatpickr-calendar {
        background-color: #2a3038 !important;
        color: #fff !important;
        border: 1px solid #444 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4) !important;
    }

    .flatpickr-weekdaycontainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        text-align: center !important;
    }

    .flatpickr-weekday {
        color: #fff !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        font-weight: 600 !important;
    }

    .flatpickr-days .dayContainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        grid-auto-rows: 38px !important; 
        justify-items: center !important;
        align-items: center !important;
    }

    .flatpickr-day {
        color: #fff !important;
        width: 32px !important;
        height: 32px !important;
        font-size: 0.85rem !important;
        line-height: 32px !important; 
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        border-radius: 50% !important;
        transition: background 0.2s ease !important;
    }

    .flatpickr-day:hover {
        background: #3a4048 !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #007bff !important;
        color: #fff !important;
    }

    .flatpickr-day.today {
        border: 1px solid #007bff !important;
    }

    .flatpickr-current-month {
        color: #fff !important;
    }

    .ver-alunos-link {
        color: #ffffffff;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
    }

    .ver-alunos-link:hover {
        color: #0056b3;
    }
    .tooltip-on-top {
        z-index: 9999 !important;
    }
</style>

<script>
    const dataTableLangUrl = "<?= base_url('assets/js/traducao-dataTable/pt_br.json'); ?>";
    const agendamentosData = <?= json_encode($agendamentos ?? []) ?>;
    const getAlunosByTurmaUrl = '<?= base_url('sys/agendamento/admin/getAlunosByTurma') ?>';

    let flatpickrEditInstance = null;
    const alunosSelecionadosEdit = new Map();
    
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            // Limpa instâncias antigas para evitar bugs
            const oldTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            if (oldTooltip) {
                oldTooltip.dispose();
            }
            // Cria a nova instância
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                container: 'body' // Garante que o tooltip apareça sobre outros elementos
            });
        });
    }

    function atualizarListaAlunosEdit() {
        const listaAlunosUl = $('#edit_lista-alunos');
        listaAlunosUl.empty();

        if (alunosSelecionadosEdit.size === 0) {
            listaAlunosUl.append('<li class="text-muted small p-2">Nenhum aluno selecionado.</li>');
        } else {
            alunosSelecionadosEdit.forEach((nome, matricula) => {
                const li = `
                    <li class="d-flex justify-content-between align-items-center p-1" data-matricula="${matricula}">
                        <span>${nome}</span>
                        <button type="button" class="btn-close btn-close-white btn-sm remove-aluno-edit"></button>
                    </li>`;
                listaAlunosUl.append(li);
            });
        }
        $('#edit_matriculas-hidden').val(Array.from(alunosSelecionadosEdit.keys()).join(','));
    }

    $(document).ready(function() {
        if (agendamentosData && agendamentosData.length > 0) {
            $('#listagem-agendamentos').DataTable({
                data: agendamentosData,
                columns: [{
                    data: 'turma_aluno',
                    render: function(data, type, row) {
                        const alunosJson = JSON.stringify(row.alunos).replace(/'/g, "&apos;");
                        const turmasAlunosJson = JSON.stringify(row.alunos_por_turma).replace(/'/g, "&apos;");
                        
                        if (row.tipo === 'turma') {
                            return `<a href="#" 
                                    class="ver-alunos-link" 
                                    data-bs-toggle="tooltip" 
                                    title="Ver Alunos" 
                                    data-alunos='${alunosJson}'><u>${data}</u></a>`;
                        
                        } else if (row.tipo === 'multi_turma') {
                            return `<a href="#" 
                                    class="ver-alunos-link" 
                                    data-bs-toggle="tooltip" 
                                    title="Ver Turmas e Alunos" 
                                    data-turmas-alunos='${turmasAlunosJson}'><u>${data}</u></a>`;
                        }
                        return data;
                    }
                }, {
                    data: 'data'
                }, {
                    data: 'status'
                }, {
                    data: 'motivo'
                }, {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const deleteInfoAttr = JSON.stringify(row.delete_info);
                        const editInfoAttr = JSON.stringify(row);

                        return `
                            <div class="d-flex justify-content-center">
                                <span data-bs-toggle="tooltip" title="Editar agendamento">
                                    <button type="button" class="btn btn-inverse-success btn-icon me-1 btn-editar-agendamento d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#modal-editar-agendamento"
                                        data-edit-info='${editInfoAttr}'>
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </span>
                                <span data-bs-toggle="tooltip" title="Excluir agendamento">
                                    <button type="button" class="btn btn-inverse-danger btn-icon me-1 btn-excluir-agendamento d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#modal-deletar-agendamento"
                                        data-nome="${row.turma_aluno}" data-delete-info='${deleteInfoAttr}'>
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
        // Modal de VISUALIZAÇÃO de Alunos
        $('#listagem-agendamentos tbody').on('click', '.ver-alunos-link', function(e) {
            e.preventDefault();
            const ul = $("#lista-alunos-modal");
            ul.empty();
            const alunos = $(this).data('alunos');
            const turmasAlunos = $(this).data('turmas-alunos');

            if (turmasAlunos) {
                for (const nomeTurma in turmasAlunos) {
                    ul.append(`<li class="list-group-item turma-header">${nomeTurma}</li>`);
                    turmasAlunos[nomeTurma].forEach(nomeAluno => ul.append(`<li class="list-group-item aluno-item">${nomeAluno}</li>`));
                }
            } else if (alunos) {
                alunos.forEach(nome => ul.append(`<li class="list-group-item">${nome}</li>`));
            }
            new bootstrap.Modal(document.getElementById("modal-ver-alunos")).show();
        });

        // Modal de EXCLUSÃO de Agendamento
        $('#listagem-agendamentos').on('click', '.btn-excluir-agendamento', function() {
            const button = $(this);
            const nome = button.data('nome');
            const deleteInfo = button.data('delete-info');
            const modal = $('#modal-deletar-agendamento');
            modal.find('#deleteAgendamentoNome').text(nome);
            modal.find('#deleteAgendamentoInfo').val(JSON.stringify(deleteInfo));
        });

        // Modal de EDIÇÃO de Agendamento
        $('#listagem-agendamentos').on('click', '.btn-editar-agendamento', function() {
            const data = $(this).data('edit-info');
            const deleteInfo = data.delete_info;
            const statusMap = { 'Disponível': '0', 'Confirmada': '1', 'Retirada': '2', 'Cancelada': '3' };
            const motivoMap = { 'Contraturno': '0', 'Estágio': '1', 'Treino': '2', 'Projeto': '3', 'Visita Técnica': '4' };

            $('#edit_original_aluno_ids').val(deleteInfo.aluno_ids.join(','));
            $('#edit_original_datas').val(deleteInfo.datas.join(','));
            $('#edit_original_motivo').val(deleteInfo.motivo);

            $('#edit_motivo').val(motivoMap[data.motivo] || deleteInfo.motivo);
            $('#edit_status').val(statusMap[data.status]);
            
            alunosSelecionadosEdit.clear();
            data.alunos.forEach((nome, index) => {
                alunosSelecionadosEdit.set(String(deleteInfo.aluno_ids[index]), nome);
            });
            atualizarListaAlunosEdit();
            const dataMaisAntiga = deleteInfo.datas[0]; 

            $('#modal-editar-agendamento').data('datas-para-selecionar', deleteInfo.datas);
            $('#modal-editar-agendamento').data('min-date-para-editar', dataMaisAntiga);
            $('#edit_datas-hidden').val(deleteInfo.datas.join(','));    
        });

        // Função pra puxar as datas selecionadas
        $('#modal-editar-agendamento').on('shown.bs.modal', function () {
            const datasParaSelecionar = $(this).data('datas-para-selecionar');
            const minDateParaEditar = $(this).data('min-date-para-editar');
            if (flatpickrEditInstance) {
                flatpickrEditInstance.destroy();
            }
            flatpickrEditInstance = flatpickr("#edit-datepicker", {
                inline: true,
                mode: "multiple",
                dateFormat: "Y-m-d",
                locale: "pt",
                // minDate: "today",
                minDate: minDateParaEditar,
                defaultDate: datasParaSelecionar,
                onChange: function(selectedDates, dateStr, instance) {
                    const datas = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));
                    $('#edit_datas-hidden').val(datas.join(','));
                }
            });
        });

        if (document.getElementById('form-cadastrar-agendamento')) {

            const alunosSelecionadosCadastro = new Map();
            function atualizarListaAlunosCadastro() {
                const listaUl = $('#lista-alunos');
                listaUl.empty();

                if (alunosSelecionadosCadastro.size === 0) {
                    listaUl.append('<li class="text-muted small p-2">Nenhum aluno selecionado.</li>');
                } else {
                    alunosSelecionadosCadastro.forEach((nome, matricula) => {
                        const li = `
                            <li data-matricula="${matricula}">
                                <span>${nome}</span>
                                <span class="remove-aluno" style="cursor:pointer; font-weight:bold; color:#dc3545;">&times;</span>
                            </li>`;
                        listaUl.append(li);
                    });
                }
                $('#matriculas-hidden').val(Array.from(alunosSelecionadosCadastro.keys()).join(','));
            }

            // Busca alunos quando uma turma é selecionada
            $('#turma_id').on('change', function() {
                const turmaId = $(this).val();
                const container = $('#alunos-container');
                container.html(turmaId ? 'Carregando...' : '');

                if (turmaId) {
                    fetch(`${getAlunosByTurmaUrl}/${turmaId}`)
                        .then(res => res.json())
                        .then(alunos => {
                            container.empty();

                            // Botão "Selecionar Todos"
                            if (alunos.length > 0) {
                                const btnTodos = $('<button type="button" class="btn btn-success btn-sm m-1">Selecionar Todos</button>');
                                btnTodos.on('click', function() {
                                    alunos.forEach(aluno => {
                                        alunosSelecionadosCadastro.set(String(aluno.matricula), aluno.nome);
                                    });
                                    atualizarListaAlunosCadastro();
                                });
                                container.append(btnTodos);
                            }
                            
                            // Botões individuais dos alunos
                            alunos.forEach(aluno => {
                                const btn = $(`<button type="button" class="btn btn-outline-primary btn-sm m-1">${aluno.nome}</button>`);
                                btn.on('click', function() {
                                    alunosSelecionadosCadastro.set(String(aluno.matricula), aluno.nome);
                                    atualizarListaAlunosCadastro();
                                });
                                container.append(btn);
                            });
                        })
                        .catch(() => container.html('<span class="text-danger">Erro ao carregar alunos.</span>'));
                }
            });

            // Remove um aluno da lista de selecionados
            $('#lista-alunos').on('click', '.remove-aluno', function() {
                const matricula = $(this).closest('li').data('matricula');
                alunosSelecionadosCadastro.delete(String(matricula));
                atualizarListaAlunosCadastro();
            });

            // Inicialização de Plugins (Flatpickr)
            flatpickr("#datepicker-container-cadastro", {
                inline: true,
                mode: "multiple",
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "pt",
                onChange: function(selectedDates, dateStr, instance) {
                    const datas = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));
                    $('#datas-hidden').val(datas.join(','));
                }
            });
            
            $('#form-cadastrar-agendamento').on('submit', function(e) {
                e.preventDefault(); // Impede o recarregamento da página
                const form = this;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload(); 
                    } else {
                        $.toast({
                            heading: 'Erro ao Salvar',
                            text: data.message || 'Verifique os dados e tente novamente.',
                            showHideTransition: 'fade',
                            icon: 'error',
                            loaderBg: '#dc3545',
                            position: 'top-center'
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    $.toast({
                        heading: 'Erro de Conexão',
                        text: 'Não foi possível se conectar ao servidor.',
                        showHideTransition: 'fade',
                        icon: 'error',
                        loaderBg: '#dc3545',
                        position: 'top-center'
                    });
                });
            });
        }

        $('#edit_turma_id').on('change', function() {
            const turmaId = $(this).val();
            const container = $('#edit_alunos-container');
            container.html(turmaId ? 'Carregando...' : '');
            
            if (turmaId) {
                fetch(`${getAlunosByTurmaUrl}/${turmaId}`)
                    .then(res => res.json())
                    .then(alunos => {
                        container.empty();
                        alunos.forEach(aluno => {
                            const btn = $(`<button type="button" class="btn btn-outline-primary btn-sm m-1">${aluno.nome}</button>`);
                            btn.on('click', function() {
                                alunosSelecionadosEdit.set(String(aluno.matricula), aluno.nome);
                                atualizarListaAlunosEdit();
                            });
                            container.append(btn);
                        });
                    }).catch(() => container.html('<span class="text-danger">Erro ao carregar.</span>'));
            }
        });
        
        $('#edit_lista-alunos').on('click', '.remove-aluno-edit', function() {
            const matricula = $(this).closest('li').data('matricula');
            alunosSelecionadosEdit.delete(String(matricula));
            atualizarListaAlunosEdit();
        });

        $(document).on('mouseover', '.flatpickr-day.flatpickr-disabled', function() {
            const el = this;
            let tooltipTitle = '';
            if ($(el).closest('#modal-editar-agendamento').length) {
                tooltipTitle = `<i class="fa fa-exclamation-triangle text-warning" style="margin-right: 6px;"></i> A data não pode ser inferior à data já cadastrada`;
            } else {
                tooltipTitle = `<i class="fa fa-exclamation-triangle text-warning" style="margin-right: 6px;"></i> A data não pode ser anterior à de hoje`;
            }
            const tooltip = new bootstrap.Tooltip(el, {
                html: true,
                title: tooltipTitle,
                trigger: 'manual',
                container: 'body',
                customClass: 'tooltip-on-top'
            });
            tooltip.show();
        });
        
        $(document).on('mouseout', '.flatpickr-day.flatpickr-disabled', function() {
            const el = this;
            const tooltip = bootstrap.Tooltip.getInstance(el);
            if (tooltip) {
                tooltip.dispose();
            }
        });
         $(document).ready(function() {
            <?php if (session()->getFlashdata('sucesso')): ?>
                $.toast({
                    heading: 'Sucesso!',
                    text: '<?= session()->getFlashdata('sucesso') ?>',
                    showHideTransition: 'fade',
                    icon: 'success',
                    loaderBg: '#28a745',
                    position: 'top-center'
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('erros')): ?>
                $.toast({
                    heading: 'Erro',
                    text: '<?= session()->getFlashdata('erros')[0]?>',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loaderBg: '#dc3545',
                    position: 'top-center'
                });
            <?php endif; ?>
        });

    });
</script>