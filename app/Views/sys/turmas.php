<div>
    <h1>Turmas</h1>
    <br>
    <button type="button" class="btn btn-primary btn-fw">Nova Turma</button>
    <br>
    <br>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>CursoId</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($turmas as $turma) : ?>
                    <tr>
                        <td><?= $prod['id']?></td>
                        <td><?= $prod['nome']?></td>
                        <td><?= $prod['curso_id']?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
</div>