<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/teste', 'Home::teste');

$routes->group('sys', function ($routes) {
    //==============================================================
    // Rotas de Cursos
    //==============================================================
    $routes->group('cursos', static function ($routes) {
        $routes->get('', 'CursoController::index');
        $routes->post('create', 'CursoController::create');
        $routes->post('update', 'CursoController::update');
        $routes->post('delete', 'CursoController::delete');
    });

    //==============================================================
    // Rotas de Turmas
    //==============================================================
    $routes->group('turmas', static function ($routes) {
        $routes->get('', 'TurmaController::index');
        $routes->post('create', 'TurmaController::create');
        $routes->post('update', 'TurmaController::update');
        $routes->delete('delete', 'TurmaController::delete');
        $routes->post('import', 'TurmaController::import');
        $routes->post('importProcess', 'TurmaController::importProcess'); 
    });

    //==============================================================
    // Rotas de Controle de Refeições
    //==============================================================
    $routes->group('controle-refeicoes', static function ($routes) {
        $routes->get('', 'ControleRefeicoesController::index');
        $routes->post('salvar', 'ControleRefeicoesController::salvar');
        $routes->post('atualizar', 'ControleRefeicoesController::atualizar');
        $routes->post('deletar', 'ControleRefeicoesController::deletar');
    });

    //==============================================================
    // Rotas de Alunos
    //==============================================================
     $routes->group('alunos', static function ($routes) {
        $routes->get('', 'AlunoController::index');
        $routes->post('create', 'AlunoController::create');
        $routes->get('edit/(:any)', 'AlunoController::edit/$1'); 
        $routes->put('update', 'AlunoController::update');
        $routes->delete('delete', 'AlunoController::delete');
    });

    //==============================================================
    // Rotas de Usuários
    //==============================================================
    $routes->group('usuarios', static function ($routes) {
        $routes->get('', 'UsuarioController::index');
        $routes->post('criar', 'UsuarioController::store');
        $routes->put('atualizar/(:num)', 'UsuarioController::update/$1');
        $routes->delete('deletar/(:num)', 'UsuarioController::delete/$1');
    });

    //==============================================================
    // Rotas de Agendamento
    //==============================================================
    $routes->group('agendamento', static function ($routes) {
        $routes->post('admin/gravar', 'AgendamentoController::gravarPorAdmin');
        $routes->post('aluno/confirmar', 'AgendamentoController::confirmarPorAluno');
        $routes->post('solicitante/solicitar', 'AgendamentoController::solicitarExcecao');
        $routes->put('admin/aprovar/(:num)', 'AgendamentoController::aprovarSolicitacao/$1');
    });

    //==============================================================
    // Rotas do Restaurante
    //==============================================================
    $routes->group('restaurante', static function ($routes) {
        $routes->post('registrar-servida', 'RefeicaoController::registrarServida');
    });

    //==============================================================
    // Rotas de Relatórios
    //==============================================================
    $routes->group('relatorios', static function ($routes) {
        $routes->get('', 'RelatorioController::index');
        $routes->get('previstos', 'RelatorioController::refeicoesPrevistas');
        $routes->get('servidos', 'RelatorioController::refeicoesServidas');
        $routes->get('nao-servidos', 'RelatorioController::refeicoesNaoServidas');
        $routes->get('confirmados', 'RelatorioController::confirmados');
    });
}); // <--- Esta chave de fechamento estava faltando