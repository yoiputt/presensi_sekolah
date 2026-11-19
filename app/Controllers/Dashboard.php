<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\PresensiModel;
use App\Models\SiswaModel;
use App\Models\TahunAjaranModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $tanggal = date('Y-m-d');

        $presensiModel = new PresensiModel();

        $totalSiswa = (new SiswaModel())->countAllResults();
        $totalKelas = (new KelasModel())->countAllResults();

        $rekap      = $presensiModel->rekapHarian($tanggal);
        $sudahAbsen = array_sum($rekap);
        $belumAbsen = max($totalSiswa - $sudahAbsen, 0);
        $persenHadir = $sudahAbsen > 0
            ? (int) round($rekap['Hadir'] / $sudahAbsen * 100)
            : 0;

        $data = [
            'title'        => 'Dashboard',
            'tanggal'      => $tanggal,
            'tahunAktif'   => (new TahunAjaranModel())->getAktif(),
            'totalSiswa'   => $totalSiswa,
            'totalKelas'   => $totalKelas,
            'rekap'        => $rekap,
            'sudahAbsen'   => $sudahAbsen,
            'belumAbsen'   => $belumAbsen,
            'persenHadir'  => $persenHadir,
            'rekapKelas'   => $presensiModel->rekapPerKelas($tanggal),
        ];

        return view('dashboard', $data);
    }
}