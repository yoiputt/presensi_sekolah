<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\PresensiModel;

class Presensi extends BaseController
{
    protected $siswaModel;
    protected $presensiModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->presensiModel = new PresensiModel();
    }

    public function index()
    {
        $kelas = $this->request->getGet('kelas');

        if ($kelas) {
    $siswa = $this->siswaModel
        ->select('siswa.*')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
        ->where('kelas.nama_kelas', $kelas)
        ->findAll();
} else {
    $siswa = $this->siswaModel->findAll();
}

        $data = [
            'title' => 'Presensi Siswa',
            'siswa' => $siswa,
            'kelas' => $kelas
        ];

        return view('presensi', $data);
    }

    public function simpan()
    {
        $tanggal = $this->request->getPost('tanggal');
        $status = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');

        if (!$tanggal || !$status) {
            return redirect()
                ->back()
                ->with('error', 'Data presensi belum lengkap.');
        }

        foreach ($status as $siswaId => $statusSiswa) {

            $data = [
                'siswa_id' => $siswaId,
                'tanggal' => $tanggal,

                'status' => $statusSiswa,
                'keterangan' => $keterangan[$siswaId] ?? null,
                'waktu' => $statusSiswa === 'Hadir'
                    ? date('H:i:s')
                    : null
            ];

            // Cek apakah siswa sudah presensi pada tanggal tersebut
            $cek = $this->presensiModel
                ->where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->first();

            if ($cek) {
                // Update
                $this->presensiModel
                    ->update($cek['id'], $data);
            } else {
                // Insert
                $this->presensiModel->insert($data);
            }
        }

        return redirect()
            ->to('/presensi')
            ->with('success', 'Presensi berhasil disimpan.');
    }
}