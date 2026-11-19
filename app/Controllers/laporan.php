<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\PresensiModel;
use CodeIgniter\HTTP\ResponseInterface;

class Laporan extends BaseController
{
    protected PresensiModel $presensiModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiModel();
        $this->kelasModel    = new KelasModel();
    }

    public function index(): string
    {
        return view('laporan/index', ['title' => 'Laporan Presensi'] + $this->dataLaporan());
    }

    /**
     * Unduh laporan sebagai CSV (bisa dibuka di Excel).
     */
    public function export(): ResponseInterface
    {
        $d = $this->dataLaporan();

        $out = fopen('php://temp', 'r+');
        fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

        fputcsv($out, ['Kelas', $d['namaKelas']], ';', '"', '\\');
        fputcsv($out, ['Periode', tanggal_indo($d['dari']) . ' s/d ' . tanggal_indo($d['sampai'])], ';', '"', '\\');
        fputcsv($out, [], ';', '"', '\\');
        fputcsv($out, ['No', 'NIS', 'Nama Siswa', 'L/P', 'Hadir', 'Sakit', 'Izin', 'Alpa', 'Total', '% Hadir'], ';', '"', '\\');

        foreach ($d['rekap'] as $i => $r) {
            $total = (int) $r['total'];

            fputcsv($out, [
                $i + 1,
                $this->csvAman($r['nis']),
                $this->csvAman($r['nama_siswa']),
                $r['jenis_kelamin'],
                (int) $r['hadir'],
                (int) $r['sakit'],
                (int) $r['izin'],
                (int) $r['alpa'],
                $total,
                $total > 0 ? round($r['hadir'] / $total * 100) . '%' : '-',
            ], ';', '"', '\\');
        }

        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        $nama = 'laporan-presensi-'
            . trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $d['namaKelas']), '-')
            . '-' . $d['dari'] . '-sd-' . $d['sampai'] . '.csv';

        return $this->response->download($nama, $csv);
    }

    /**
     * Menyiapkan filter dan data rekap (dipakai halaman dan export).
     */
    private function dataLaporan(): array
    {
        $kelasList = $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll();

        $idKelas = (int) $this->request->getGet('kelas');
        if ($idKelas === 0 && $kelasList !== []) {
            $idKelas = (int) $kelasList[0]['id_kelas'];
        }

        $dari   = tanggal_valid($this->request->getGet('dari'), date('Y-m-01'));
        $sampai = tanggal_valid($this->request->getGet('sampai'), date('Y-m-d'));

        if ($dari > $sampai) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $namaKelas = '-';
        foreach ($kelasList as $k) {
            if ((int) $k['id_kelas'] === $idKelas) {
                $namaKelas = $k['nama_kelas'];
                break;
            }
        }

        $rekap = $idKelas > 0
            ? $this->presensiModel->rekapPerSiswa($idKelas, $dari, $sampai)
            : [];

        $jumlah = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpa' => 0];
        foreach ($rekap as $r) {
            foreach ($jumlah as $key => $_) {
                $jumlah[$key] += (int) $r[$key];
            }
        }

        return [
            'kelasList' => $kelasList,
            'idKelas'   => $idKelas,
            'namaKelas' => $namaKelas,
            'dari'      => $dari,
            'sampai'    => $sampai,
            'rekap'     => $rekap,
            'jumlah'    => $jumlah,
        ];
    }

    /**
     * Cegah CSV injection: awalan = + - @ dianggap rumus oleh Excel.
     */
    private function csvAman(string $nilai): string
    {
        return preg_match('/^[=+\-@\t\r]/', $nilai) ? "'" . $nilai : $nilai;
    }
}