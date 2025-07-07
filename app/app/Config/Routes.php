<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', static function ($routes) {
    // Coasters
    $routes->post('coasters', '\\App\\UI\\Controllers\\CoasterController::create');
    $routes->get('coasters/(:segment)', '\\App\\UI\\Controllers\\CoasterController::view/$1');

    // Wagons
    $routes->post('coasters/(:segment)/wagons', '\\App\\UI\\Controllers\\WagonController::add/$1');
    $routes->delete('coasters/(:segment)/wagons/(:segment)', '\\App\\UI\\Controllers\\WagonController::remove/$1/$2');
});
