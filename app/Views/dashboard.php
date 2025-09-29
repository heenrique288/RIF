<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>IFRO Calama - Sistemas - by Calama Devs</title>

    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/mdi/css/materialdesignicons.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/css/vendor.bundle.base.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/select2/select2.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/css/modern-vertical/style.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/font-awesome/css/font-awesome.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/vendors/jquery-toast-plugin/jquery.toast.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/css/custom.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/css/flatpickr.min.css"); ?>">

    <link rel="shortcut icon" href="<?php echo base_url("assets/images/logo-ifro-mini.png"); ?>" />

    <script src="<?php echo base_url("assets/vendors/js/vendor.bundle.base.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/jquery-validation/jquery.validate.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/jquery-toast-plugin/jquery.toast.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/select2/select2.min.js"); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/typeahead.js/typeahead.bundle.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/typeahead.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/flatpickr.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/flatpickr.ptbr.js') ?>"></script>
    

    <style>
        /* Ajuste para o texto digitado ficar cinza */
        .select2-container--default .select2-search--dropdown .select2-search__field,
        .select2-container--default .select2-search--inline .select2-search__field {
            color: #999 !important;
            background-color: transparent !important;
        }

        .sidebar .nav.sub-menu {
            padding: 0 0 0 2rem;
        }

        .sidebar .nav .nav-item .nav-link .menu-title {
            font-size: 0.80rem;
        }

        /* Estilo para os cards de estatísticas */
        .statistics-details>div {
            background-color: #202124;
            /* Fundo preto sólido para alto contraste */
            padding: 15px;
            border-radius: 8px;
            flex-grow: 1;
            /* Para que ocupem o espaço disponível */
            margin-right: 15px;
            /* Adiciona espaçamento entre os cards */
        }

        /* Remover a margem direita do último item */
        .statistics-details>div:last-child {
            margin-right: 0;
        }

        /* Adicionar espaçamento entre a linha de cards de estatísticas e os gráficos */
        .statistics-details {
            margin-bottom: 25px;
            /* Adiciona margem inferior para separar dos gráficos */
        }

        /* Fundo para os cards de gráficos */
        .card.card-rounded {
            background-color: #202124;
            /* Fundo preto sólido para alto contraste */
            border-radius: 8px;
        }
    </style>

    <?php if (isset($graficoPassado) && isset($graficoFuturo)) : ?>
        <script>
            var graficoPassadoData = <?= json_encode($graficoPassado) ?>;
            var graficoFuturoData = <?= json_encode($graficoFuturo) ?>;
        </script>
    <?php endif; ?>

</head>

<body>
    <div class="container-scroller min-vh-100">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a href="<?php echo base_url("/") ?>">
                    <img src="<?php echo base_url("assets/images/logo-ifro.png"); ?>" class="sidebar-brand brand-logo" alt="logo" />
                    <img src="<?php echo base_url("assets/images/logo-ifro-mini.png"); ?>" class="sidebar-brand brand-logo-mini" alt="logo" />
                </a>
            </div>
            <ul class="nav">

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("/") ?>" style="margin-top:20px;">
                        <span class="menu-icon">
                            <i class="mdi mdi-home" style="color: #0090e7"></i>
                        </span>
                        <span class="menu-title">Página inicial</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#refeicoes" aria-expanded="false" aria-controls="refeicoes">
                        <span class="menu-icon">
                            <i class="mdi mdi-silverware-clean" style="color: #00d25b"></i>
                        </span>
                        <span class="menu-title">Gestão de Refeições</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="refeicoes">
                        <ul class="nav flex-column sub-menu">

                            <?php if (auth()->user()->inGroup('admin', 'restaurante')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/agendamento'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-calendar-month-outline"></i>
                                        </span>
                                        <span class="menu-title">Agendamentos</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                             <?php if (auth()->user()->inGroup('admin', 'restaurante')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/analise'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-magnify"></i>
                                        </span>
                                        <span class="menu-title">Análise de Solicitações</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (auth()->user()->inGroup('admin', 'solicitante')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/solicitacoes'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-chart-line"></i>
                                        </span>
                                        <span class="menu-title">Solicitações</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (auth()->user()->inGroup('admin', 'restaurante')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('#'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-license"></i>
                                        </span>
                                        <span class="menu-title">Entregas</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>

                <?php if (auth()->user()->inGroup('admin')): ?>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#cadastros" aria-expanded="false" aria-controls="cadastros">
                            <span class="menu-icon">
                                <i class="fa fa-database" style="color: #8f5fe8"></i>
                            </span>
                            <span class="menu-title">Cadastros</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="cadastros">

                            <ul class="nav flex-column sub-menu">

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/usuarios'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-account"></i>
                                        </span>
                                        <span class="menu-title">Usuários</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/alunos'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-account-group"></i>
                                        </span>
                                        <span class="menu-title">Alunos</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/cursos'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-school"></i>
                                        </span>
                                        <span class="menu-title">Cursos</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/turmas'); ?>">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-account-group"></i>
                                        </span>
                                        <span class="menu-title">Turmas</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (auth()->user()->inGroup('admin')): ?>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#op_avancadas" aria-expanded="false" aria-controls="op_avancadas">
                            <span class="menu-icon">
                                <i class="fa fa-gears" style="color: #fc424a"></i>
                            </span>
                            <span class="menu-title">Avançado</span>
                            <i class="menu-arrow"></i>
                        </a>

                        <div class="collapse" id="op_avancadas">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('sys/admin'); ?>">
                                        <span class="menu-icon">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <span class="menu-title">Usuários do Sistema</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span class="menu-icon">
                                            <i class="mdi mdi-backup-restore"></i>
                                        </span>
                                        <span class="menu-title">Backup</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span class="menu-icon">
                                            <i class="fa fa-trash"></i>
                                        </span>
                                        <span class="menu-title">Limpeza</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="<?php echo base_url("/sys/home") ?>"><img src="<?php echo base_url("assets/images/logo-ifro-mini.png"); ?>" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button id="navbar-toggle" class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>

                    <ul class="navbar-nav navbar-nav-right">

                        <li class="nav-item dropdown">
                            <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                                <div class="navbar-profile">
                                    <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo auth()->user()->username;
                                                                                            ?></p>
                                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                                <h6 class="p-3 mb-0">Perfil</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-cog text-success"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Configurações</p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="<?php echo base_url('logout'); ?>">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Sair</p>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if (isset($content)) : ?>
                        <?php echo $content; ?>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="home-tab">
                                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Visão Geral</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content tab-content-basic">
                                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="statistics-title">Turmas Hoje</p>
                                                            <h3 class="rate-percentage"><?= $turmasHoje ?></h3>
                                                        </div>
                                                        <div>
                                                            <p class="statistics-title">Alunos Confirmados</p>
                                                            <h3 class="rate-percentage"><?= $alunosConfirmados ?></h3>
                                                        </div>
                                                        <div>
                                                            <p class="statistics-title">Solicitações Pendentes</p>
                                                            <h3 class="rate-percentage"><?= $solicitacoesPendentes ?></h3>
                                                        </div>
                                                        <div class="d-none d-md-block">
                                                            <p class="statistics-title">Não Retiraram Refeição (30 dias)</p>
                                                            <h3 class="rate-percentage"><?= $naoRetiradasUltimos30Dias ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 d-flex flex-column">
                                                    <div class="row flex-grow">
                                                        <div class="col-12 col-lg-4 col-lg-12">
                                                            <div class="card card-rounded">
                                                                <div class="card-body">
                                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <h4 class="card-title card-title-dash text-light">Gráfico - Últimos 7 Dias</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="chartjs-wrapper mt-4" style="height: 300px; overflow: hidden;">
                                                                        <canvas id="graficoPassado"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 d-flex flex-column">
                                                    <div class="row flex-grow">
                                                        <div class="col-12 col-lg-4 col-lg-12">
                                                            <div class="card card-rounded">
                                                                <div class="card-body">
                                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <h4 class="card-title card-title-dash text-light">Gráfico - Próximos 7 Dias</h4>
                                                                        </div>
                                                                    </div>
                                                                    <div class="chartjs-wrapper mt-4" style="height: 300px; overflow: hidden;">
                                                                        <canvas id="graficoFuturo"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">

                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright© 2025~ <a href="javascript: void()">Calama Dev's</a>.</span>

                        <?php //if (auth()->user()->inGroup('admin')):
                        ?>
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            [CI <?php echo CodeIgniter\CodeIgniter::CI_VERSION ?>] |
                            [PHP <?php echo phpversion(); ?>] |
                            [Database <?php echo \Config\Database::connect()->getVersion(); ?>]
                        </span>
                        <?php //endif;
                        ?>

                        <span class="text-muted float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Feito a mão e com <i class="mdi mdi-heart text-danger"></i></span>

                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url("assets/vendors/chart.js/chart.umd.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/progressbar.js/progressbar.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/jvectormap/jquery-jvectormap.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/datatables.net/jquery.dataTables.js"); ?>"></script>
    <script src="<?php echo base_url("assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"); ?>"></script>

    <script src="<?php echo base_url("assets/js/off-canvas.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/hoverable-collapse.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/misc.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/settings.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/todolist.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/tabs.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/file-upload.js"); ?>"></script>

    <script src="<?php echo base_url("assets/js/dashboard.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/form-validation.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/validacoes/cadastro-professor.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/validacoes/edicao-professor.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/validacoes/cadastro-disciplina.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/validacoes/cadastro-cursos.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/dashboards/dashboards.js"); ?>"></script>

    <?php if (isset($graficoPassado) && isset($graficoFuturo)) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Configurações globais para os gráficos
                Chart.defaults.color = 'rgba(255, 255, 255, 0.7)';
                Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

                // Gráfico passado - Últimos 7 dias (GRÁFICO DE BARRAS)
                var ctxPassado = document.getElementById('graficoPassado').getContext('2d');
                new Chart(ctxPassado, {
                    type: 'bar',
                    data: {
                        labels: graficoPassadoData.labels,
                        datasets: [{
                            label: 'Refeições Previstas',
                            data: graficoPassadoData.previstas,
                            backgroundColor: '#FFD700', // COR AMARELA
                        }, {
                            label: 'Refeições Confirmadas',
                            data: graficoPassadoData.confirmadas,
                            backgroundColor: '#0090e7',
                        }, {
                            label: 'Refeições Servidas',
                            data: graficoPassadoData.servidas,
                            backgroundColor: '#00d25b',
                        }, {
                            label: 'Refeições Canceladas',
                            data: graficoPassadoData.canceladas,
                            backgroundColor: '#fc424a',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Gráfico futuro - Próximos 7 dias (GRÁFICO DE BARRAS)
                var ctxFuturo = document.getElementById('graficoFuturo').getContext('2d');
                new Chart(ctxFuturo, {
                    type: 'bar',
                    data: {
                        labels: graficoFuturoData.labels,
                        datasets: [{
                            label: 'Refeições Previstas',
                            data: graficoFuturoData.previstas,
                            backgroundColor: '#FFD700', // COR AMARELA
                        }, {
                            label: 'Refeições Confirmadas',
                            data: graficoFuturoData.confirmadas,
                            backgroundColor: '#0090e7',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Correção para o gráfico de rosca de Salas
            const doughnutChartSalas = document.getElementById("disponibilidade-salas");
            if (doughnutChartSalas) {
                // Verifica se o gráfico já foi inicializado pelo dashboard.js
                // A biblioteca Chart.js anexa o objeto do gráfico ao elemento canvas
                if (doughnutChartSalas.chart) {
                    const reservadas = parseInt(doughnutChartSalas.getAttribute('data-reserv'));
                    const disponiveis = parseInt(doughnutChartSalas.getAttribute('data-disp'));
                    const indisponiveis = parseInt(doughnutChartSalas.getAttribute('data-indisp'));

                    doughnutChartSalas.chart.data.datasets[0].data = [reservadas, disponiveis, indisponiveis];
                    doughnutChartSalas.chart.update();
                }
            }

            // Correção para o gráfico de rosca de Professores
            const doughnutChartProfessores = document.getElementById("disponibilidade-professores");
            if (doughnutChartProfessores) {
                // Verifica se o gráfico já foi inicializado pelo dashboard.js
                if (doughnutChartProfessores.chart) {
                    const disponiveis = parseInt(doughnutChartProfessores.getAttribute('data-disp'));
                    const indisponiveis = parseInt(doughnutChartProfessores.getAttribute('data-indisp'));

                    doughnutChartProfessores.chart.data.datasets[0].data = [disponiveis, indisponiveis];
                    doughnutChartProfessores.chart.update();
                }
            }
        });
    </script>

    <script>

    </script>
</body>

</html>