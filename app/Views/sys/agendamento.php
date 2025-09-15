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
            <th>Status</th>
            <th>Motivo</th>
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
        grid-auto-rows: 42px !important;
        justify-items: center !important;
        align-items: center !important;
    }

    .flatpickr-day {
        color: #fff !important;
        width: 36px !important;
        height: 36px !important;
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
                    container: 'body'
                });
            });
        };

        if (agendamentosData.length > 0) {
            $('#listagem-agendamentos').DataTable({
                data: agendamentosData,
                columns: [{
                    data: 'turma_aluno',
                    render: function(data, type, row) {
                        const alunosJson = JSON.stringify(row.alunos).replace(/'/g, "&apos;");
                        if (row.tipo === 'turma') {
                            return `<a href="#" class="ver-alunos-turma ver-alunos-link" data-alunos='${alunosJson}'><u>${data}</u></a>`;
                        } else if (row.alunos && row.alunos.length > 1) {
                            return `${data} <a href="#" class="ver-alunos-turma ver-alunos-link" data-alunos='${alunosJson}'>+ ${row.alunos.length - 1} aluno(s)</a>`;
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
                        return `
                            <div class="d-flex">
                                <span data-bs-toggle="tooltip" title="Editar agendamento">
                                    <button type="button" class="btn btn-inverse-success btn-icon me-1"><i class="fa fa-edit"></i></button>
                                </span>
                                <span data-bs-toggle="tooltip" title="Excluir agendamento">
                                    <button type="button" class="btn btn-inverse-danger btn-icon me-1"><i class="fa fa-trash"></i></button>
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

    $('#listagem-agendamentos tbody').on('click', '.ver-alunos-turma', function(e) {
        e.preventDefault();
        const alunos = $(this).data('alunos');
        const ul = $("#lista-alunos-modal");
        ul.empty();

        if (Array.isArray(alunos) && alunos.length > 0) {
            alunos.forEach(nome => {
                ul.append(`<li class="list-group-item" style="background-color: #2a3038; color: #ffffff;">${nome}</li>`);
            });
        } else {
            ul.append('<li class="list-group-item">Nenhum aluno encontrado.</li>');
        }

        const modalAlunos = new bootstrap.Modal(document.getElementById("modal-ver-alunos"));
        modalAlunos.show();
    });

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

    if (document.getElementById('turma_id')) {
        document.getElementById('turma_id').addEventListener('change', function() {
            let turmaId = this.value;
            let container = document.getElementById('alunos-container');
            container.innerHTML = 'Carregando...';

            if (turmaId) {
                fetch('<?= base_url('sys/agendamento/admin/getAlunosByTurma') ?>/' + turmaId)
                    .then(res => res.json())
                    .then(data => {
                        container.innerHTML = '';

                        if (data.length > 0) {
                            let btnTodos = document.createElement('button');
                            btnTodos.type = 'button';
                            btnTodos.className = 'btn btn-success btn-sm m-1';
                            btnTodos.textContent = 'Selecionar todos';
                            container.appendChild(btnTodos);
                            btnTodos.addEventListener('click', function() {
                                data.forEach(aluno => selecionarAluno(aluno));
                            });
                        }

                        data.forEach(aluno => {
                            let btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn btn-outline-primary btn-sm m-1';
                            btn.textContent = aluno.nome;
                            btn.dataset.matricula = aluno.matricula;
                            btn.addEventListener('click', function() {
                                selecionarAluno(aluno);
                            });
                            container.appendChild(btn);
                        });

                        function selecionarAluno(aluno) {
                            let lista = document.getElementById('lista-alunos');
                            if (!document.querySelector(`#lista-alunos li[data-matricula="${aluno.matricula}"]`)) {
                                let li = document.createElement('li');
                                li.dataset.matricula = aluno.matricula;
                                li.innerHTML = `<span>${aluno.nome}</span><span class="remove-aluno">&times;</span>`;
                                lista.appendChild(li);

                                let hidden = document.getElementById('matriculas-hidden');
                                let values = hidden.value ? hidden.value.split(',') : [];
                                values.push(aluno.matricula);
                                hidden.value = values.join(',');

                                li.querySelector('.remove-aluno').addEventListener('click', function() {
                                    li.remove();
                                    let values = hidden.value.split(',').filter(m => m != aluno.matricula);
                                    hidden.value = values.join(',');
                                });
                            }
                        }
                    })
                    .catch(() => container.innerHTML = 'Erro ao carregar alunos');
            } else {
                container.innerHTML = '';
            }
        });
    }

    if (document.getElementById("inline-datepicker")) {
        flatpickr("#inline-datepicker", {
            inline: true,
            mode: "multiple",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                let datasSelecionadas = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));
                document.getElementById('datas-hidden').value = datasSelecionadas.join(',');
            }
        });
    }

    if (document.getElementById("form-cadastrar-agendamento")) {
        document.getElementById("form-cadastrar-agendamento").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
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
                            heading: 'Erro',
                            text: data.message || 'Erro ao salvar agendamento.',
                            showHideTransition: 'fade',
                            icon: 'error',
                            loaderBg: '#dc3545',
                            position: 'top-center'
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    $.toast({
                        heading: 'Erro',
                        text: 'Erro ao conectar com o servidor.',
                        showHideTransition: 'fade',
                        icon: 'error',
                        loaderBg: '#dc3545',
                        position: 'top-center'
                    });
                });
        });
    }
</script>