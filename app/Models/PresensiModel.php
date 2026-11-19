<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    public const STATUS = ['Hadir', 'Sakit', 'Izin', 'Alpa'];

    protected $table            = 'presensi';
    protected $primaryKey       = 'id_presensi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_siswa',
        'id_guru',
        'id_tahun',
        'tanggal',
        'status',
        'keterangan',
    ];

    /**
     * Semua siswa dalam satu kelas beserta status presensinya pada tanggal tertentu.
     * Siswa yang belum diabsen tetap muncul dengan status NULL.
     */
    public function getByKelasTanggal(int $idKelas, string $tanggal): array
    {
        return $this->db->table('siswa s')
            ->select('s.id_siswa, s.nis, s.nama_siswa, s.jenis_kelamin, p.id_presensi, p.status, p.keterangan')
            ->join(
                'presensi p',
                'p.id_siswa = s.id_siswa AND p.tanggal = ' . $this->db->escape($tanggal),
                'left',
                false
            )
            ->where('s.id_kelas', $idKelas)
            ->orderBy('s.nama_siswa', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Simpan presensi banyak siswa sekaligus.
     * Memakai unique key (id_siswa, tanggal): data baru di-insert, data lama di-update.
     *
     * @param list<array<string, mixed>> $rows
     */
    public function simpanBatch(array $rows): bool
    {
        if ($rows === []) {
            return false;
        }

        $this->db->transStart();
        $this->builder()->upsertBatch($rows);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Rekap jumlah per status pada satu tanggal (untuk dashboard).
     *
     * @return array{Hadir:int, Sakit:int, Izin:int, Alpa:int}
     */
    public function rekapHarian(string $tanggal): array
    {
        $hasil = array_fill_keys(self::STATUS, 0);

        $rows = $this->select('status, COUNT(*) AS jumlah')
            ->where('tanggal', $tanggal)
            ->groupBy('status')
            ->findAll();

        foreach ($rows as $row) {
            $hasil[$row['status']] = (int) $row['jumlah'];
        }

        return $hasil;
    }

    /**
     * Rekap per siswa dalam satu kelas untuk rentang tanggal (untuk laporan).
     */
    public function rekapPerSiswa(int $idKelas, string $dari, string $sampai): array
    {
        return $this->db->table('siswa s')
            ->select(
                's.id_siswa, s.nis, s.nama_siswa, s.jenis_kelamin,
                COALESCE(SUM(p.status = \'Hadir\'), 0) AS hadir,
                COALESCE(SUM(p.status = \'Sakit\'), 0) AS sakit,
                COALESCE(SUM(p.status = \'Izin\'), 0)  AS izin,
                COALESCE(SUM(p.status = \'Alpa\'), 0)  AS alpa,
                COUNT(p.id_presensi) AS total',
                false
            )
            ->join(
                'presensi p',
                'p.id_siswa = s.id_siswa AND p.tanggal BETWEEN '
                    . $this->db->escape($dari) . ' AND ' . $this->db->escape($sampai),
                'left',
                false
            )
            ->where('s.id_kelas', $idKelas)
            ->groupBy('s.id_siswa')
            ->orderBy('s.nama_siswa', 'ASC')
            ->get()
            ->getResultArray();
    }
        /**
     * Rekap presensi per kelas pada satu tanggal (untuk dashboard).
     */
    public function rekapPerKelas(string $tanggal): array
    {
        return $this->db->table('kelas k')
            ->select(
                'k.id_kelas, k.nama_kelas, k.wali_kelas,
                COUNT(DISTINCT s.id_siswa) AS total_siswa,
                COALESCE(SUM(p.status = \'Hadir\'), 0) AS hadir,
                COALESCE(SUM(p.status = \'Sakit\'), 0) AS sakit,
                COALESCE(SUM(p.status = \'Izin\'), 0)  AS izin,
                COALESCE(SUM(p.status = \'Alpa\'), 0)  AS alpa,
                COUNT(p.id_presensi) AS sudah_absen',
                false
            )
            ->join('siswa s', 's.id_kelas = k.id_kelas', 'left')
            ->join(
                'presensi p',
                'p.id_siswa = s.id_siswa AND p.tanggal = ' . $this->db->escape($tanggal),
                'left',
                false
            )
            ->groupBy('k.id_kelas')
            ->orderBy('k.nama_kelas', 'ASC')
            ->get()
            ->getResultArray();
    }
}