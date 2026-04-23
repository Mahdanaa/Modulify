<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Web\AuthController::index');
$routes->post('/login/process', 'Web\AuthController::process');
$routes->get('/logout', 'Web\AuthController::logout');
$routes->get('/materi/(:segment)', 'Web\MateriController::baca/$1');

$routes->group('master', ['filter' => 'authGuard'], function($routes) {

    $routes->get('/', 'Web\MasterController::index');
    $routes->get('create', 'Web\MasterController::create');
    $routes->post('store', 'Web\MasterController::store');
    $routes->post('delete/(:num)', 'Web\MasterController::delete/$1');
    $routes->get('edit/(:num)', 'Web\MasterController::edit/$1');
    $routes->post('update/(:num)', 'Web\MasterController::update/$1');

    $routes->group('detail', function($routes) {
        $routes->get('create/(:num)', 'Web\MasterController::createDetail/$1');
        $routes->get('edit/(:num)', 'Web\MasterController::editDetail/$1');
        $routes->post('upload', 'Web\MasterController::uploadGambar');
        $routes->post('store/(:num)', 'Web\MasterController::storeDetail/$1');
        $routes->post('toggle/(:num)', 'Web\MasterController::toggleVisibility/$1');
        $routes->post('update/(:num)', 'Web\MasterController::updateDetail/$1');
        $routes->post('delete/(:num)', 'Web\MasterController::deleteDetail/$1');
        $routes->get('(:num)', 'Web\MasterController::detail/$1');
    });
});
