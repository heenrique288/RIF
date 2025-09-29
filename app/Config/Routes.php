<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/teste', 'Home::teste');

// Shield Auth routes
service('auth')->routes($routes);
service('auth')->routes($routes, ['except' => ['login', 'register']]);

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
        $routes->post('delete', 'TurmaController::delete');
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
        $routes->post('import', 'AlunoController::import');
        $routes->post('importProcess', 'AlunoController::importProcess');
        
        //provisorio
        $routes->get('sendEmail', 'AlunoController::enviarEmail'); 
        
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
        $routes->get('', 'AgendamentoController::index');
        $routes->post('admin/create', 'AgendamentoController::create');
        $routes->get('admin/getAlunosByTurma/(:num)', 'AgendamentoController::getAlunosByTurma/$1');
        $routes->post('admin/update', 'AgendamentoController::update');
        $routes->post('admin/delete', 'AgendamentoController::delete');
    });

    //==============================================================
    // Rotas de Solicitação de Refeições
    //==============================================================
    $routes->group('solicitacoes', static function ($routes) {
        $routes->get('', 'SolicitacaoRefeicoesController::index');
        $routes->post('create', 'SolicitacaoRefeicoesController::create');
        $routes->post('update', 'SolicitacaoRefeicoesController::update');
        $routes->post('delete', 'SolicitacaoRefeicoesController::delete');
    });

      //==============================================================
    // Rotas de Análise de solicitação
    //==============================================================
   $routes->get('analise', 'AnaliseSolicitacaoController::index');

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

    //==============================================================
    // Rotas do Admin para o gerenciamento de usuários
    //==============================================================

    $routes->group('admin', function ($routes) {
        $routes->get('/', 'AdminController::index'); // Página inicial da admin
        $routes->post('alterar-grupo', 'AdminController::alterarGrupoUsuario'); // Atribuir a um grupo de usuários
        $routes->post('atualizar-usuario', 'AdminController::atualizarUsuario'); 
        $routes->post('resetar-senha', 'AdminController::resetarSenha'); // Atualizar senha
        $routes->post('desativar-usuario', 'AdminController::desativarUsuario');
        $routes->post('registrar-usuario', 'AdminController::registrarUsuario');
        $routes->get('usuarios-inativos', 'AdminController::usuariosInativos');
        $routes->post('reativar-usuario', 'AdminController::reativarUsuario');
        $routes->post('excluir-permanentemente', 'AdminController::excluirPermanentemente');
    });
});

//Rota do Webhook
$routes->post('webhook/response', 'WebhookController::response');