<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');

$routes->get('/logout', 'Auth::logout');

$routes->post('/proses-login', 'Login::proses');

$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/presensi', 'Presensi::index');
$routes->post('/presensi/simpan', 'Presensi::simpan');
$routes->get('dashboard', 'Dashboard::index');

$routes->get('siswa', 'Siswa::index');
$routes->post('siswa/hapus/(:num)', 'Siswa::hapus/$1');
$routes->post('siswa/tambah', 'Siswa::tambah');

$routes->get('presensi', 'Presensi::index');

$routes->get('laporan', 'Laporan::index');