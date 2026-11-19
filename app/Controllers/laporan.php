<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['siswa'] = $db->table('siswa')
                            ->get()
                            ->getResultArray();

        $data['totalSiswa'] = count($data['siswa']);

        return view('laporan', $data);
    }
}