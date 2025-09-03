<?php echo view('components/gerenciamento-usuarios/modal-cad-user.php'); ?>

<!-- mostrar ALERT em caso de erro -->
<?php if (session()->has('erros')): ?>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('erros') as $erro): ?>
                                <li> <i class="mdi mdi-alert-circle"></i><?php echo esc($erro); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<h1>Gerenciamento de Usuário</h1>
<div class="my-4">
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cad-user">
        <i class="fa fa-plus-circle btn-icon-prepend"></i>
        Adicionar Usuário
    </button>
</div>

<!-- início da tabela -->
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-4" id="listagem-usuarios">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Grupo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>