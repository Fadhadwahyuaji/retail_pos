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
    // User CRUD
    $routes->get('user', 'Admin\UserController::index');
    $routes->get('user/create', 'Admin\UserController::create');
    $routes->post('user/store', 'Admin\UserController::store');
    $routes->get('user/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('user/update/(:num)', 'Admin\UserController::update/$1');
    $routes->get('user/delete/(:num)', 'Admin\UserController::delete/$1');
    $routes->get('user/toggle-status/(:num)', 'Admin\UserController::toggleStatus/$1');
    // Change Password
    $routes->get('user/change-password/(:num)', 'Admin\UserController::changePassword/$1');
    $routes->post('user/update-password/(:num)', 'Admin\UserController::updatePassword/$1');
    // AJAX endpoints
    $routes->get('user/get-by-outlet', 'Admin\UserController::getUsersByOutlet');
    // Statistics (optional)
    $routes->get('user/stats', 'Admin\UserController::stats');

    // Outlet CRUD 
    $routes->get('outlet', 'Admin\OutletController::index');
    $routes->get('outlet/index', 'Admin\OutletController::index');
    $routes->get('outlet/create', 'Admin\OutletController::create');
    $routes->post('outlet/store', 'Admin\OutletController::store');
    $routes->get('outlet/edit/(:num)', 'Admin\OutletController::edit/$1');
    $routes->post('outlet/update/(:num)', 'Admin\OutletController::update/$1');
    $routes->get('outlet/delete/(:num)', 'Admin\OutletController::delete/$1');
    $routes->get('outlet/toggle-status/(:num)', 'Admin\OutletController::toggleStatus/$1');

    // Barang CRUD 
    $routes->get('barang', 'Admin\BarangController::index');
    $routes->get('barang/create', 'Admin\BarangController::create');
    $routes->post('barang/store', 'Admin\BarangController::store');
    $routes->get('barang/detail/(:segment)', 'Admin\BarangController::detail/$1');
    $routes->get('barang/edit/(:segment)', 'Admin\BarangController::edit/$1');
    $routes->post('barang/update/(:segment)', 'Admin\BarangController::update/$1');
    $routes->get('barang/delete/(:segment)', 'Admin\BarangController::delete/$1');
    $routes->get('barang/toggle-status/(:segment)', 'Admin\BarangController::toggleStatus/$1');
    $routes->get('barang/toggle-ready/(:segment)', 'Admin\BarangController::toggleReady/$1');
    // AJAX endpoints
    $routes->get('barang/search', 'Admin\BarangController::search');
    $routes->get('barang/get-by-barcode', 'Admin\BarangController::getByBarcode');

    // Promo CRUD
    $routes->get('promo', 'Admin\PromoController::index');
    $routes->get('promo/create', 'Admin\PromoController::create');
    $routes->post('promo/store', 'Admin\PromoController::store');
    $routes->get('promo/detail/(:segment)', 'Admin\PromoController::detail/$1');
    $routes->get('promo/edit/(:segment)', 'Admin\PromoController::edit/$1');
    $routes->post('promo/update/(:segment)', 'Admin\PromoController::update/$1');
    $routes->get('promo/delete/(:segment)', 'Admin\PromoController::delete/$1');
    $routes->get('promo/toggle-status/(:segment)', 'Admin\PromoController::toggleStatus/$1');
    // Promo Items Management
    $routes->get('promo/items/(:segment)', 'Admin\PromoController::items/$1');
    $routes->post('promo/add-item', 'Admin\PromoController::addItem');
    $routes->post('promo/remove-item', 'Admin\PromoController::removeItem');
    $routes->post('promo/calculate-discount', 'Admin\PromoController::calculateDiscount');
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
