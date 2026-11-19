<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\PresensiModel;
use App\Models\SiswaModel;
use App\Models\TahunAjaranModel;
use CodeIgniter\HTTP\RedirectResponse;

class Presensi extends BaseController
{
    protected PresensiModel $presensiModel;
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiModel();
        $this->siswaModel    = new SiswaModel();
        $this->kelasModel    = new KelasModel();
    }

    public function index(): string
    {
        $kelasList = $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll();

        // Default: kelas pertama
        $idKelas = (int) $this->request->getGet('kelas');
        if ($idKelas === 0 && $kelasList !== []) {
            $idKelas = (int) $kelasList[0]['id_kelas'];
        }

        // Tanggal tidak boleh melewati hari ini
        $hari    = date('Y-m-d');
        $tanggal = tanggal_valid($this->request->getGet('tanggal'), $hari);
        if ($tanggal > $hari) {
            $tanggal = $hari;
        }

        $siswa = $idKelas > 0
            ? $this->presensiModel->getByKelasTanggal($idKelas, $tanggal)
            : [];

        return view('presensi/index', [
            'title'      => 'Presensi Siswa',
            'kelasList'  => $kelasList,
            'idKelas'    => $idKelas,
            'tanggal'    => $tanggal,
            'hari'       => $hari,
            'siswa'      => $siswa,
            'statusList' => PresensiModel::STATUS,
        ]);
    }

    public function simpan(): RedirectResponse
    {
        $idKelas = (int) $this->request->getPost('id_kelas');
        $tanggal = tanggal_valid($this->request->getPost('tanggal'), '');

        if ($tanggal === '' || $tanggal > date('Y-m-d')) {
            return redirect()->to('presensi')->with('error', 'Tanggal presensi tidak valid.');
        }

        $kembali = 'presensi?kelas=' . $idKelas . '&tanggal=' . $tanggal;

        $tahun = (new TahunAjaranModel())->getAktif();
        if ($tahun === null) {
            return redirect()->to($kembali)
                ->with('error', 'Belum ada tahun ajaran aktif. Aktifkan satu tahun ajaran terlebih dahulu.');
        }

        $status     = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');

        if (! is_array($status) || $status === []) {
            return redirect()->to($kembali)->with('error', 'Data presensi belum lengkap.');
        }

        // Hanya siswa yang benar-benar milik kelas ini yang boleh disimpan
        $idSiswaValid = array_map(
            'intval',
            array_column(
                $this->siswaModel->select('id_siswa')->where('id_kelas', $idKelas)->findAll(),
                'id_siswa'
            )
        );

        $idGuru = (int) session()->get('id_guru');
        $rows   = [];

        foreach ($status as $idSiswa => $nilai) {
            $idSiswa = (int) $idSiswa;

            if (! in_array($idSiswa, $idSiswaValid, true)
                || ! in_array($nilai, PresensiModel::STATUS, true)) {
                continue;
            }

            $ket = (is_array($keterangan) && isset($keterangan[$idSiswa]) && is_string($keterangan[$idSiswa]))
                ? trim($keterangan[$idSiswa])
                : '';

            $rows[] = [
                'id_siswa'   => $idSiswa,
                'id_guru'    => $idGuru,
                'id_tahun'   => (int) $tahun['id_tahun'],
                'tanggal'    => $tanggal,
                'status'     => $nilai,
                'keterangan' => $ket !== '' ? mb_substr($ket, 0, 255) : null,
            ];
        }

        if ($rows === []) {
            return redirect()->to($kembali)->with('error', 'Tidak ada data presensi yang valid untuk disimpan.');
        }

        if (! $this->presensiModel->simpanBatch($rows)) {
            return redirect()->to($kembali)->with('error', 'Presensi gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->to($kembali)
            ->with('success', count($rows) . ' data presensi tanggal ' . tanggal_indo($tanggal) . ' berhasil disimpan.');
    }
}