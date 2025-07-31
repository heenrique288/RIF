<div>
    <h1>Cursos</h1>
    <p>Total de cursos cadastrados: <?php echo count($cursos) ?></p>
    <button type="button" class="btn btn-primary btn-fw" data-bs-toggle="modal" data-bs-target="#cursoModal">
        Novo Curso
    </button>
</div>
<?= $this->include('components/cursos/modal_cad_curso', ['cursos' => $cursos]) ?>