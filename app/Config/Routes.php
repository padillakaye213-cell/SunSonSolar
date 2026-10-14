<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::signin');
$routes->get('register', 'Auth::register');
$routes->get('signin', 'Auth::signin');
$routes->post('auth/attemptRegister', 'Auth::attemptRegister');
$routes->post('auth/attemptSignin', 'Auth::attemptSignin');
$routes->get('logout', 'Auth::logout');

$routes->get('admin/dashboard', 'Dashboard::admin');
$routes->get('employee/dashboard', 'Dashboard::employee');
$routes->get('customer/dashboard', 'Dashboard::customer');