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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

    .ver-justificativa {
        color: #ffffffff;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
    }

    .ver-justificativa:hover {
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
                    data: 'data',
                    render: function(data, type, row) {
                        if (data) {
                            const date = new Date(data + 'T00:00:00');
                            return date.toLocaleDateString('pt-BR');
                        }
                        return data;
                    }
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

    // Lógica de seleção de alunos
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
                            li.innerHTML = `
                                <span>${aluno.nome}</span>
                                <span class="remove-aluno">&times;</span>
                            `;
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

    // Lógica para adicionar mais campos de data
    let datasSelecionadas = [];

    flatpickr("#inline-datepicker", {
        inline: true,
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            datasSelecionadas = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));
            document.getElementById('datas-hidden').value = datasSelecionadas.join(',');
        }
    });

    // Lógica para submissão do formulário -> OBS: EXEMPLO VISUAL DE LISTAGEM
    document.getElementById("form-cadastrar-agendamento").addEventListener("submit", function(e) {
        e.preventDefault();

        let turmaSelect = document.getElementById("turma_id");
        let turmaOuAluno = turmaSelect.options[turmaSelect.selectedIndex].text;

        let alunosSelecionados = [];
        document.querySelectorAll("#lista-alunos li span:first-child").forEach(el => {
            alunosSelecionados.push(el.textContent);
        });

        let alunosHtml = "";
        if (alunosSelecionados.length > 0) {
            if (alunosSelecionados.length === 1) {
                turmaOuAluno = alunosSelecionados[0];
            } else {
                turmaOuAluno = alunosSelecionados[0] + ` <a href="#" class="ver-mais-alunos" data-alunos='${JSON.stringify(alunosSelecionados)}'> + ${alunosSelecionados.length - 1}  alunos</a>`;
            }
        }

        let datas = document.getElementById("datas-hidden").value;
        let crc = document.getElementById("crc").value;
        let codigo = document.getElementById("codigo").value;
        let justificativa = document.getElementById("justificativa").value;

        // Monta a nova linha
        let tbody = document.querySelector("#listagem-agendamentos tbody");
        if (!tbody) {
            tbody = document.createElement("tbody");
            document.getElementById("listagem-agendamentos").appendChild(tbody);
        }

        let tr = document.createElement("tr");
        tr.innerHTML = `
        <td>${turmaOuAluno}</td>
        <td>${datas}</td>
        <td>${crc}</td>
        <td>${codigo}</td>
        <td>
            <a href="#" class="ver-justificativa" data-justificativa='${justificativa.replace(/'/g, "\\'")}'>Ver justificativa</a>
        </td>
        <td>
            <div class="d-flex">
                <span data-bs-toggle="tooltip" title="Editar agendamento">
                    <button type="button"
                        class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1">
                        <i class="fa fa-edit"></i>
                    </button>
                </span>
                <span data-bs-toggle="tooltip" title="Excluir agendamento">
                    <button type="button"
                        class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon">
                        <i class="fa fa-trash"></i>
                    </button>
                </span>
            </div>
        </td>
    `;

        tbody.appendChild(tr);

        var modal = bootstrap.Modal.getInstance(document.getElementById('modal-cadastrar-agendamento'));
        modal.hide();

        this.reset();
        document.getElementById("lista-alunos").innerHTML = "";
        document.getElementById("matriculas-hidden").value = "";
        document.getElementById("datas-hidden").value = "";
    });

    // Lógica para ver mais alunos no modal
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("ver-mais-alunos")) {
            e.preventDefault();

            let alunos = JSON.parse(e.target.dataset.alunos);
            let ul = document.getElementById("lista-alunos-modal");
            ul.innerHTML = "";
            alunos.forEach(nome => {
                let li = document.createElement("li");
                li.className = "list-group-item";
                li.textContent = nome;
                ul.appendChild(li);
            });

            let modal = new bootstrap.Modal(document.getElementById("modal-ver-alunos"));
            modal.show();
        }
    });

    // Lógica para ver justificativa no modal
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("ver-justificativa")) {
            e.preventDefault();

            let justificativa = e.target.dataset.justificativa;
            document.getElementById("texto-justificativa").textContent = justificativa;

            let modal = new bootstrap.Modal(document.getElementById("modal-ver-justificativa"));
            modal.show();
        }
    });
</script>