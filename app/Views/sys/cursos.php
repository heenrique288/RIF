<?php echo view('components/cursos/modal_cadastrar_curso') ?>
<?php echo view('components/cursos/modal_editar_curso') ?>
<?php echo view('components/cursos/modal_deletar_curso') ?>

<div>
    <h1>Cursos</h1>

    <?php if (session()->has('erros')): ?>
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session('erros') as $erro): ?>
                            <li> <i class="mdi mdi-alert-circle"></i><?= esc($erro) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="my-4">
        <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-curso">
            <i class="fa fa-plus-circle btn-icon-prepend"></i>
            Novo Curso
        </button>
    </div>

    <table class="table mb-4" id="listagem-cursos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (isset($cursos) && !empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= esc($curso['id']) ?></td>
                        <td><?= esc($curso['nome']) ?></td>
                        <td>
                            <div class="d-flex">
                                <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados do curso">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-editar-curso"
                                        data-id="<?php echo esc($curso['id']); ?>"
                                        data-nome="<?php echo esc($curso['nome']); ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </span>

                                <span data-bs-toggle="tooltip" data-placement="top" title="Excluir curso">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-deletar-curso"
                                        data-id="<?php echo esc($curso['id']); ?>"
                                        data-nome="<?php echo esc($curso['nome']); ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </span>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<script>
    $(document).ready(function() {

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

    });
</script>