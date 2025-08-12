<?php echo view('components/solicitacoes/modal_cadastrar_solicitacao', ["turmas" => $turmas]) ?>
<?php echo view('components/solicitacoes/modal_editar_solicitacao', ["turmas" => $turmas]) ?>
<?php echo view('components/solicitacoes/modal_deletar_solicitacao') ?>

<div>

    <h1>Solicitações de Refeições</h1>

    <div class="my-4">
        <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#modal-cadastrar-solicitacao">
            <i class="fa fa-plus-circle btn-icon-prepend"></i>
            Nova Solicitação
        </button>
    </div>

    <table class="table mb-4" id="listagem-solicitacoes">
        <thead>
            <tr>
                <th>Código</th>
                <th>Status</th>
                <th>Turma_id</th>
                <th>Data</th>
                <th>CRC</th>
                <th>Código</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (isset($solicitacoes) && !empty($solicitacoes)): ?>
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <tr>
                        <td><?= esc($solicitacao['id']) ?></td>
                        <td><?= esc($solicitacao['status']) ?></td>
                        <td><?= esc($solicitacao['turma_id']) ?></td>
                        <td><?= esc($solicitacao['data_refeicao']) ?></td>
                        <td><?= esc($solicitacao['crc']) ?></td>
                        <td><?= esc($solicitacao['codigo']) ?></td>
                        <td>
                            <div class="d-flex">
                                <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados do solicitacao">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-editar-solicitacao"
                                        data-id="<?php echo esc($solicitacao['id']); ?>"
                                        data-status="<?php echo esc($solicitacao['status']); ?>"
                                        data-turma_id="<?php echo esc($solicitacao['turma_id']); ?>"
                                        data-data_refeicao="<?php echo esc($solicitacao['data_refeicao']); ?>"
                                        data-crc="<?php echo esc($solicitacao['crc']); ?>"
                                        data-codigo="<?php echo esc($solicitacao['codigo']); ?>"
                                        data-justificativa="<?php echo esc($solicitacao['justificativa']); ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </span>

                                <span data-bs-toggle="tooltip" data-placement="top" title="Excluir solicitacao">
                                    <button
                                        type="button"
                                        class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-deletar-solicitacao"
                                        data-id="<?php echo esc($solicitacao['id']); ?>">
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