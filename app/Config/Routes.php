<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---------- Auth ----------
$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('proses-login', 'Login::proses');
$routes->post('logout', 'Auth::logout');

// ---------- Dashboard ----------
$routes->get('dashboard', 'Dashboard::index');

// ---------- Data Siswa ----------
$routes->get('siswa', 'Siswa::index');
$routes->get('siswa/tambah', 'Siswa::tambah');
$routes->post('siswa/tambah', 'Siswa::simpan');
$routes->get('siswa/edit/(:num)', 'Siswa::edit/$1');
$routes->post('siswa/edit/(:num)', 'Siswa::update/$1');
$routes->post('siswa/hapus/(:num)', 'Siswa::hapus/$1');

// ---------- Data Kelas ----------
$routes->get('kelas', 'Kelas::index');
$routes->get('kelas/tambah', 'Kelas::tambah');
$routes->post('kelas/tambah', 'Kelas::simpan');
$routes->get('kelas/edit/(:num)', 'Kelas::edit/$1');
$routes->post('kelas/edit/(:num)', 'Kelas::update/$1');
$routes->post('kelas/hapus/(:num)', 'Kelas::hapus/$1');

// ---------- Presensi ----------
$routes->get('presensi', 'Presensi::index');
$routes->post('presensi/simpan', 'Presensi::simpan');

// ---------- Laporan ----------
$routes->get('laporan', 'Laporan::index');
$routes->get('laporan/export', 'Laporan::export');