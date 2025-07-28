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
