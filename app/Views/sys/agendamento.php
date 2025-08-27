<?php echo view('components/agendamentos/modal_cadastrar_agendamento', ["turmas" => $turmas], ["alunos" => $alunos]) ?>


<h1>Agendamento de Refeição</h1>
<div class="my-4">
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-agendamento">
        <i class="fa fa-plus-circle btn-icon-prepend"></i>
        Novo Agendamento
    </button>
</div>

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
        <tr>
            <td>Exemplo de exibição do nome : Joao</td>
            <td>Exemplo de exibição da data : 2025-08-27</td>
            <td>123456</td>
            <td>7890</td>
            <td>Refeição especial</td>
            <td>
                <div class="d-flex">
                    <!-- Editar -->
                    <span data-bs-toggle="tooltip" title="Editar agendamento">
                        <button type="button"
                            class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-agendamento"
                            data-id="1"
                            data-data="2025-08-27"
                            data-turma="Turma A"
                            data-alunos='["João","Maria"]'>
                            <i class="fa fa-edit"></i>
                        </button>
                    </span>

                    <!-- Excluir -->
                    <span data-bs-toggle="tooltip" title="Excluir agendamento">
                        <button type="button"
                            class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-deletar-agendamento"
                            data-id="1">
                            <i class="fa fa-trash"></i>
                        </button>
                    </span>
                </div>
            </td>

        </tr>
    </tbody>

</table>


<!-- Modal para ver todos os alunos -->
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

<!-- Modal para ver justificativa -->
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
    /* Estilo da lista de alunos selecionados */
    #lista-alunos-modal li {
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
</style>