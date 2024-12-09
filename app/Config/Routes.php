<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Database Creation Route
$routes->get('create-db', function () {
    $forge = \config\Database::forge();
    if ($forge->createDatabase('dustira'));
});

// Authentication Routes
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/register', 'Auth::register');

// Protected Routes (Requires Authentication)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('dashboard/show_modal/(:num)', 'Dashboard::show_modal/$1');
    $routes->post('dashboard/tambah_stok', 'Dashboard::tambah_stok');

    // Data Management Routes
    // Data Pegawai
    $routes->get('data_pegawai', 'DataManagement\DataPegawai::index');
    $routes->get('tambah_pegawai', 'DataManagement\DataPegawai::tambah_pegawai');
    $routes->post('store_pegawai', 'DataManagement\DataPegawai::store');
    $routes->get('edit_pegawai/(:num)', 'DataManagement\DataPegawai::edit_pegawai/$1');
    $routes->post('update_pegawai', 'DataManagement\DataPegawai::update_pegawai');
    $routes->delete('delete_pegawai/(:num)', 'DataManagement\DataPegawai::delete_pegawai/$1');

    // Data Barang
    $routes->get('data_barang', 'DataManagement\DataBarang::index');
    $routes->get('tambah_barang', 'DataManagement\DataBarang::tambah_barang');
    $routes->post('DataBarang/store_barang', 'DataManagement\DataBarang::store_barang');
    $routes->get('edit_barang/(:num)', 'DataManagement\DataBarang::edit_barang/$1');
    $routes->post('update_barang', 'DataManagement\DataBarang::update_barang');
    $routes->delete('delete_barang/(:num)', 'DataManagement\DataBarang::delete_barang/$1');
    $routes->get('get_barang_data/(:num)', 'DataManagement\DataBarang::getDataBarang/$1');


    // Data Bahan
    $routes->get('data_bahan', 'DataManagement\DataBahan::index');
    $routes->get('tambah_bahan', 'DataManagement\DataBahan::tambah_bahan');
    $routes->post('store_bahan', 'DataManagement\DataBahan::store_bahan');
    $routes->get('edit_bahan/(:num)', 'DataManagement\DataBahan::edit_bahan/$1');
    $routes->post('update_bahan', 'DataManagement\DataBahan::update_bahan');
    $routes->delete('delete_bahan/(:num)', 'DataManagement\DataBahan::delete_bahan/$1');
    $routes->get('get_bahan_data/(:num)', 'DataManagement\DataBahan::getBahanData/$1');



    // Data Mesin
    $routes->get('data_mesin', 'DataManagement\DataMesin::dataMesin');
    $routes->get('tambah_mesin', 'DataManagement\DataMesin::tambahMesin');
    $routes->post('storeMesin', 'DataManagement\DataMesin::storeMesin');
    $routes->get('DataManagement/DataMesin/getBahanOptions', 'DataManagement\DataMesin::getBahanOptions');
    $routes->post('DataManagement/DataMesin/storeBahan', 'DataManagement\DataMesin::storeBahan');
    $routes->delete('deleteMesin/(:num)', 'DataManagement\DataMesin::deleteMesin/$1');
    $routes->get('DataManagement/DataMesin/getBahanOptions/(:num)', 'DataManagement\DataMesin::getExistingBahan/$1');
    $routes->put('updateStatus/(:num)', 'DataManagement\DataMesin::updateStatus/$1');

    // Data Ruangan
    $routes->get('data_ruangan', 'DataManagement\DataRuangan::index');
    $routes->get('tambah_ruangan', 'DataManagement\DataRuangan::tambahRuangan');
    $routes->post('create', 'DataManagement\DataRuangan::create');
    $routes->post('storeRuangan', 'DataManagement\DataRuangan::storeRuangan');
    $routes->post('store', 'DataManagement\DataRuangan::store');
    $routes->put('updateStatus/(:num)', 'DataManagement\RuanganController::updateStatus/$1');
    $routes->delete('deleteRuangan/(:num)', 'DataManagement\DataRuangan::delete/$1');
    $routes->get('DataManagement/DataRuangan/getBarangOptions', 'DataManagement\DataRuangan::getBarangOptions');
    $routes->get('DataManagement/DataRuangan/getExistingBarang/(:num)', 'DataManagement\DataRuangan::getExistingBarang/$1');


    // Pengelolaan Routes
    $routes->group('pengelolaan', function ($routes) {
        // Dashboard Pengelolaan
        $routes->get('pengelola', 'Pengelolaan\Pengelola::index');

        // Pencucian
        $routes->get('pencucian', 'Pengelolaan\Pencucian::index');
        $routes->post('pencucian/start/', 'Pengelolaan\Pencucian::start');
        $routes->post('pencucian/StatMove/(:num)', 'Pengelolaan\Pencucian::StatMove/$1');
        $routes->get('pencucian/move/(:num)', 'Pengelolaan\Pencucian::move/$1');

        // Pengeringan
        $routes->get('pengeringan', 'Pengelolaan\Pengeringan::index');
        $routes->post('pengeringan/start/(:num)', 'Pengelolaan\Pengeringan::start/$1');
        $routes->post('pengeringan/StatMove/(:num)', 'Pengelolaan\Pengeringan::StatMove/$1');
        $routes->get('pengeringan/move/(:num)', 'Pengelolaan\Pengeringan::move/$1');

        // Penyetrikaan
        $routes->get('penyetrikaan', 'Pengelolaan\Penyetrikaan::index');
        $routes->post('penyetrikaan/start/(:num)', 'Pengelolaan\Penyetrikaan::start/$1');
        $routes->post('penyetrikaan/StatMove/(:num)', 'Pengelolaan\Penyetrikaan::StatMove/$1');
        $routes->get('penyetrikaan/move/(:num)', 'Pengelolaan\Penyetrikaan::move/$1');

        // Pelipatan
        $routes->get('pelipatan', 'Pengelolaan\Pelipatan::index');
        $routes->post('pelipatan/start/(:num)', 'Pengelolaan\Pelipatan::start/$1');
        $routes->post('pelipatan/statMove/(:num)', 'Pengelolaan\Pelipatan::statMove/$1');
        $routes->post('pelipatan/move/(:num)', 'Pengelolaan\Pelipatan::move/$1');
        $routes->get('pelipatan/detail/(:num)', 'Pengelolaan\Pelipatan::detail/$1');
        $routes->post('pelipatan/addDetail/(:num)', 'Pengelolaan\Pelipatan::addDetail/$1');
        $routes->delete('pelipatan/delete_detail/(:num)', 'Pengelolaan\Pelipatan::delete_detail/$1');
    });

    // Distribusi Routes
    $routes->group('distribusi', function ($routes) {
        $routes->get('/', 'Distribusi\Distribusi::index');

        // Timbangan Kotor
        $routes->get('timbangan_kotor', 'Distribusi\Timbangan::timbangan_kotor');
        $routes->get('tambah_timbangan', 'Distribusi\Timbangan::tambah_timbangan');
        $routes->post('timbangan/store', 'Distribusi\Timbangan::store');
        $routes->get('timbangan/reject/(:num)', 'Distribusi\Timbangan::reject/$1');
        $routes->get('timbangan/approve/(:num)', 'Distribusi\Timbangan::approve/$1');

        // Timbangan Bersih
        $routes->get('timbangan_bersih', 'Distribusi\TimbanganBersih::timbangan_bersih');
        $routes->get('timbangan_bersih/detail/(:num)', 'Distribusi\TimbanganBersih::detail/$1');
        $routes->post('timbangan_bersih/postDetail/(:num)', 'Distribusi\TimbanganBersih::postDetail/$1');
        $routes->get('timbangan_bersih/statMove/(:num)', 'Distribusi\TimbanganBersih::statMove/$1');
        $routes->get('timbangan_bersih/delivered/(:num)', 'Distribusi\TimbanganBersih::delivered/$1');

        $routes->get('pengiriman/konfirmasi/(:num)', 'Distribusi\Pengiriman::konfirmasi/$1');
        $routes->post('pengiriman/simpan_konfirmasi/(:num)', 'Distribusi\Pengiriman::simpan_konfirmasi/$1');
        $routes->get('pengiriman/konfirmasi/refresh-csrf', 'Distribusi\Pengiriman::refreshCsrf');
    });
    //Laporan   
    $routes->group('laporan',  function ($routes) {
        $routes->get('laporan', 'Laporan\Laporan::index');
        $routes->get('laporan_pengelolaan', 'Laporan\LaporanPengelolaan::index');
        $routes->get('laporan_distribusi', 'Laporan\LaporanDistribusi::index');
        $routes->get('laporan-distribusi/detail/(:num)', 'Laporan\LaporanDistribusi::detail/$1');
        $routes->get('laporan-pengelolaan/detail/(:num)', 'Laporan\LaporanPengelolaan::detail/$1');
    });
});
