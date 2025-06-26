<head>
    <style>
        .table-striped td,
        .table-striped th {
            padding-top: 15px;
            padding-bottom: 15px;
        }
    </style>
</head>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title" style="text-align: center">Estatística do Almoço</h4>
                    <div class="position-relative">
                        <div class="daoughnutchart-wrapper">
                            <canvas id="transaction-history" class="transaction-chart"></canvas>
                        </div>
                        <div class="custom-value">200 <span>Total de Alunos Previstos</span>
                        </div>
                    </div>
                    <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                        <div class="text-md-center text-xl-left">
                            <h6 class="mb-1">Segunda</h6>
                            <p class="text-muted mb-0">01 Jul 2025, 09:12AM</p>
                        </div>
                        <div class="align-self-center flex-grow text-end text-md-center text-xl-right py-md-2 py-xl-0">
                            <h6 class="font-weight-bold mb-0">150</h6>
                        </div>
                    </div>
                    <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                        <div class="text-md-center text-xl-left">
                            <h6 class="mb-1">Terça</h6>
                            <p class="text-muted mb-0">30 Jun 2025, 09:12AM</p>
                        </div>
                        <div class="align-self-center flex-grow text-end text-md-center text-xl-right py-md-2 py-xl-0">
                            <h6 class="font-weight-bold mb-0">113</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title mb-1">Quantidade de Alunos por Curso</h4>
                        <p class="text-muted mb-1">Data de Hoje</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="preview-list">
                                <div class="preview-item border-bottom">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-primary">
                                            <i class="mdi mdi-laptop"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-sm-flex flex-grow">
                                        <div class="flex-grow">
                                            <h6 class="preview-subject">Informática</h6>
                                            <p class="text-muted mb-0">60 alunos</p>
                                        </div>
                                        <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                            <p class="text-muted" style="text-align: center;">15 min atrás</p>
                                            <p class="text-muted mb-0">30 entregue, 30 pendente </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-item border-bottom">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-success">
                                            <i class="mdi mdi-lightbulb-outline"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-sm-flex flex-grow">
                                        <div class="flex-grow">
                                            <h6 class="preview-subject">Eletrotécnica</h6>
                                            <p class="text-muted mb-0">60 alunos</p>
                                        </div>
                                        <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                            <p class="text-muted" style="text-align: center;">2 min atrás</p>
                                            <p class="text-muted mb-0">23 entregue, 37 pendentes </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-item border-bottom">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-info">
                                            <i class="mdi mdi-test-tube"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-sm-flex flex-grow">
                                        <div class="flex-grow">
                                            <h6 class="preview-subject">Química</h6>
                                            <p class="text-muted mb-0">80 alunos</p>
                                        </div>
                                        <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                            <p class="text-muted" style="text-align: center;">35 min atrás</p>
                                            <p class="text-muted mb-0">75 entregue, 5 pendentes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-warning">
                                            <i class="mdi mdi-wall"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-sm-flex flex-grow">
                                        <div class="flex-grow">
                                            <h6 class="preview-subject">Edificações </h6>
                                            <p class="text-muted mb-0">0 alunos </p>
                                        </div>
                                        <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                            <p class="text-muted" style="text-align: center;">0 min atrás</p>
                                            <p class="text-muted mb-0">0 entregue, 0 pendentes </p>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="card-title" style="padding: 20px 0px 20px 0px;">Cursos com contraturno Hoje </h4>
                                <button type="button" class="btn btn-primary btn-icon-text">
                                    <i class="mdi mdi-laptop"></i> Info </button>
                                <button type="button" class="btn btn-info btn-icon-text"> Quim <i class="mdi mdi-test-tube"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-icon-text">
                                    <i class="mdi mdi-lightbulb-outline"></i> Eltro </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" style="text-align: center; padding: 10px 0px 10px 0px;">Estatística por Aluno</h4>
                </p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> Foto </th>
                                <th> Nome e sobrenome </th>
                                <th> Matrícula </th>
                                <th> Hora </th>
                                <th> Data </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-1.png') ?>" alt="image">
                                </td>
                                <td> Herman Beck </td>
                                <td> 2025106809087 </td>
                                <td> 11:11 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-2.png') ?>" alt="image">
                                </td>
                                <td> Messsy Adam </td>
                                <td> 2025106809087</td>
                                <td> 11:11 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-3.png') ?>" alt="image">
                                </td>
                                <td> John Richards </td>
                                <td> 2025106809087 </td>
                                <td> 11:11 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-4.png') ?>" alt="image">
                                </td>
                                <td> Peter Meggik </td>
                                <td> 2025106809087 </td>
                                <td> 11:10 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-1.png') ?>" alt="image">
                                </td>
                                <td> Edward </td>
                                <td> 2025106809087 </td>
                                <td> 11:10 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-2.png') ?>" alt="image">
                                </td>
                                <td> John Doe </td>
                                <td> 2025106809087 </td>
                                <td> 11:10 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                            <tr>
                                <td class="py-1">
                                    <img src="<?= base_url('assets/images/faces-clipart/pic-3.png') ?>" alt="image">
                                </td>
                                <td> Henry Tom </td>
                                <td> 2025106809087 </td>
                                <td> 11:10 </td>
                                <td> Jun 26, 2025 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("transaction-history").getContext("2d");

        // Destroi gráfico anterior se existir ---- O uso de window.transactionChart é para evitar conflitos com o gráfico anterior. (Verificar se não prejudica o código)
        if (window.transactionChart) {
            window.transactionChart.destroy();
        }

        window.transactionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Total_Utilizadas', 'Total_Emitidas', 'Total_Sobras'], // nomes usados no tooltip
                datasets: [{
                    data: [150, 30, 20], // valores das fatias -- (Aqui a soma tem que dar o total de alunos)
                    backgroundColor: ['#e53935', '#43a047', '#1e88e5'], // cores das fatias
                    borderColor: "#191c24"
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // <- desativa a legenda visível
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                return `${label}: ${value} alunos`;
                            }
                        }
                    }
                },
                cutout: '70%' // mantém o "buraco" no centro do gráfico
            }
        });
    });
</script>