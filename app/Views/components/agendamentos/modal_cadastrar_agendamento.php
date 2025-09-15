<div class="modal fade" id="modal-cadastrar-agendamento" tabindex="-1" role="dialog" aria-labelledby="modal-cadastrar-agendamento-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-cadastrar-agendamento-label">Cadastrar Novo Agendamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-cadastrar-agendamento" class="forms-sample" method="post" action="<?php echo base_url('sys/agendamento/admin/create'); ?>">
                <?php echo csrf_field() ?>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="turma_id" class="form-label">Turma</label>
                        <select id="turma_id" name="turma_id" class="form-select py-2" required>
                            <option value="">Selecione a turma</option>
                            <?php foreach ($turmas as $turma): ?>
                                <option value="<?php echo $turma['id'] ?>">
                                    <?= esc($turma['nome_turma']) . ' - ' . esc($turma['nome_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Aluno(s)</label>
                        <div id="alunos-container">
                        </div>
                        <div id="alunos-selecionados" class="mt-2">
                            <label>Selecionados:</label>
                            <ul id="lista-alunos" class="list-unstyled">
</ul>
                        </div>
                    </div>

                    <input type="hidden" name="matriculas[]" id="matriculas-hidden">

                    <div class="mb-3">
                        <label class="form-label">Data(s) do Agendamento</label>
                        <div id="inline-datepicker"></div>
                        <input type="hidden" name="datas[]" id="datas-hidden">
                    </div>

                    <div class="mb-3 d-flex gap-3">
                        <div class="flex-fill">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select py-2" id="status" name="status" required>
                                <option value="" selected disabled>Selecione o Status</option>
                                <option value="0">Disponível</option>
                                <option value="1">Confirmada</option>
                                <option value="2">Retirada</option>
                                <option value="3">Cancelada</option>
                            </select>
                        </div>
                        <div class="flex-fill">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-select py-2" id="motivo" name="motivo" required>
                                <option value="" selected disabled>Selecione o motivo</option>
                                <option value="0">Contraturno</option>
                                <option value="1">Estágio</option>
                                <option value="2">Treino</option>
                                <option value="3">Projeto</option>
                                <option value="4">Visita Técnica</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    /* Estilo da lista de alunos selecionados */
    #lista-alunos li {
        background-color: #2a3038;
        /* Azul clarinho */
        color: #ffffff;
        /* Texto preto para melhor contraste */
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
        /* vermelho */
        font-weight: bold;
        margin-left: 1rem;
    }

    #lista-alunos li .remove-aluno:hover {
        color: #a71d2a;
    }

    /* Fundo geral do calendário */
    .flatpickr-calendar {
        background-color: #2a3038 !important;
        color: #fff !important;
        border: 1px solid #444 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4) !important;
    }

    /* Cabeçalho - dias da semana */
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

    /* Dias do mês */
    .flatpickr-days .dayContainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        grid-auto-rows: 42px !important;
        justify-items: center !important;
        /* Força centralizar na célula */
        align-items: center !important;
        /* Centraliza vertical */
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

    /* Hover em um dia */
    .flatpickr-day:hover {
        background: #3a4048 !important;
    }

    /* Dia selecionado */
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #007bff !important;
        /* azul igual do botão */
        color: #fff !important;
    }

    /* Dia atual (hoje) */
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
                        // Botão "Selecionar todos"
                        let btnTodos = document.createElement('button');
                        btnTodos.type = 'button';
                        btnTodos.className = 'btn btn-success btn-sm m-1';
                        btnTodos.textContent = 'Selecionar todos';
                        container.appendChild(btnTodos);

                        btnTodos.addEventListener('click', function() {
                            data.forEach(aluno => selecionarAluno(aluno));
                        });
                    }

                    // Botões individuais de alunos
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

                    // Função que adiciona aluno à lista
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

                            // Remover aluno
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

    //
    //

    // Lógica para adicionar mais campos de data
    let datasSelecionadas = [];

    flatpickr("#inline-datepicker", {
        inline: true, // mostra o calendário dentro do modal
        mode: "multiple", // permite selecionar várias datas
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            // Atualiza o hidden input com as datas selecionadas
            datasSelecionadas = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));
            document.getElementById('datas-hidden').value = datasSelecionadas.join(',');
        }
    });


    //
    //

    // Lógica para ver mais alunos no modal

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("ver-mais-alunos")) {
            e.preventDefault();

            let alunos = JSON.parse(e.target.dataset.alunos);
            let ul = document.getElementById("lista-alunos-modal");
            ul.innerHTML = "";
            alunos.forEach(nome => {
                let li = document.createElement("li"); // Aqui ele faz a listagem dos alunos que foram "cadastrados"
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
