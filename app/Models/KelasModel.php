<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id_kelas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_kelas', 'wali_kelas'];

    protected $validationRules = [
        'nama_kelas' => 'required|max_length[30]',
        'wali_kelas' => 'permit_empty|max_length[120]',
    ];

    protected $validationMessages = [
        'nama_kelas' => [
            'required' => 'Nama kelas wajib diisi.',
        ],
    ];

    /**
     * Semua kelas beserta jumlah siswanya.
     */
    public function getWithJumlahSiswa(): array
    {
        return $this->select('kelas.*, COUNT(siswa.id_siswa) AS jumlah_siswa')
            ->join('siswa', 'siswa.id_kelas = kelas.id_kelas', 'left')
            ->groupBy('kelas.id_kelas')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->findAll();
    }
}