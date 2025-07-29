<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/teste', 'Home::teste');

$routes->group('/cursos', function ($routes) {
    $routes->get('/', 'CursoController::index');
    $routes->post('/criar', 'CursoController::store');
    $routes->put('/atualizar', 'CursoController::update');
    $routes->delete('/deletar', 'CursoController::update');
});

$routes->group('/turmas', function ($routes) {
    $routes->get('/', 'TurmaController::index');
    $routes->post('/criar', 'TurmaController::store');
    $routes->put('/atualizar', 'TurmaController::update');
    $routes->delete('/deletar', 'TurmaController::delete');
});
