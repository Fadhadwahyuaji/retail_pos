<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');

// Protected routes (perlu login)
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Admin routes (hanya Admin Pusat - AD)
$routes->group('admin', ['filter' => 'role:AD'], function ($routes) {
    $routes->get('manajemen-user', 'Admin\UserManagementController::index');
    $routes->get('manajemen-user/create', 'Admin\UserManagementController::create');
    $routes->post('manajemen-user/store', 'Admin\UserManagementController::store');
    $routes->get('manajemen-user/edit/(:num)', 'Admin\UserManagementController::edit/$1');
    $routes->post('manajemen-user/update/(:num)', 'Admin\UserManagementController::update/$1');
    $routes->delete('manajemen-user/delete/(:num)', 'Admin\UserManagementController::delete/$1');

    $routes->get('outlet', 'Admin\OutletController::index');
    $routes->get('outlet/index', 'Admin\OutletController::index');
    $routes->get('outlet/create', 'Admin\OutletController::create');
    $routes->post('outlet/store', 'Admin\OutletController::store');
    $routes->get('outlet/edit/(:num)', 'Admin\OutletController::edit/$1');
    $routes->post('outlet/update/(:num)', 'Admin\OutletController::update/$1');
    $routes->get('outlet/delete/(:num)', 'Admin\OutletController::delete/$1');
    $routes->get('outlet/toggle-status/(:num)', 'Admin\OutletController::toggleStatus/$1');

    // Alternative routes for manajemen-outlet
    // $routes->get('manajemen-outlet', 'Admin\OutletController::index');
    // $routes->get('manajemen-outlet/create', 'Admin\OutletController::create');
    // $routes->post('manajemen-outlet/store', 'Admin\OutletController::store');
    // $routes->get('manajemen-outlet/edit/(:num)', 'Admin\OutletController::edit/$1');
    // $routes->post('manajemen-outlet/update/(:num)', 'Admin\OutletController::update/$1');
    // $routes->get('manajemen-outlet/delete/(:num)', 'Admin\OutletController::delete/$1');
});

// Manajer routes (Admin Pusat dan Manajer Outlet - AD, MG)
$routes->group('manajer', ['filter' => 'role:MG'], function ($routes) {
    $routes->get('laporan-outlet', 'Manajer\LaporanController::index');
    $routes->get('laporan-outlet/detail/(:num)', 'Manajer\LaporanController::detail/$1');
    $routes->get('laporan-outlet/export', 'Manajer\LaporanController::export');
});

// Kasir routes (semua role bisa akses - AD, MG, KS)
$routes->group('kasir', ['filter' => 'role:KS'], function ($routes) {
    $routes->get('transaksi-pos', 'Kasir\TransaksiController::index');
    $routes->post('transaksi-pos/process', 'Kasir\TransaksiController::process');
    $routes->get('transaksi-pos/history', 'Kasir\TransaksiController::history');
});
