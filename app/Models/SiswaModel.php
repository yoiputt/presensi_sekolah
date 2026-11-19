<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id_siswa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nis', 'nama_siswa', 'id_kelas', 'jenis_kelamin'];

    // Saat update, sertakan 'id_siswa' di data agar is_unique mengabaikan baris sendiri.
    protected $validationRules = [
        'nis'           => 'required|max_length[20]|is_unique[siswa.nis,id_siswa,{id_siswa}]',
        'nama_siswa'    => 'required|max_length[120]',
        'id_kelas'      => 'required|is_natural_no_zero|is_not_unique[kelas.id_kelas]',
        'jenis_kelamin' => 'required|in_list[L,P]',
    ];

    protected $validationMessages = [
        'nis' => [
            'required'  => 'NIS wajib diisi.',
            'is_unique' => 'NIS sudah terdaftar.',
        ],
        'nama_siswa' => [
            'required' => 'Nama siswa wajib diisi.',
        ],
        'id_kelas' => [
            'required'      => 'Kelas wajib dipilih.',
            'is_not_unique' => 'Kelas tidak ditemukan.',
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin wajib dipilih.',
            'in_list'  => 'Jenis kelamin tidak valid.',
        ],
    ];

    /**
     * Daftar siswa beserta nama kelas, dengan filter opsional.
     */
    public function getWithKelas(?int $idKelas = null, ?string $keyword = null): array
    {
        $this->select('siswa.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = siswa.id_kelas');

        if ($idKelas) {
            $this->where('siswa.id_kelas', $idKelas);
        }

        if ($keyword !== null && $keyword !== '') {
            $this->groupStart()
                ->like('siswa.nama_siswa', $keyword)
                ->orLike('siswa.nis', $keyword)
                ->groupEnd();
        }

        return $this->orderBy('kelas.nama_kelas', 'ASC')
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();
    }

    /**
     * Satu siswa beserta nama kelasnya.
     */
    public function findWithKelas(int $id): ?array
    {
        return $this->select('siswa.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
            ->where('siswa.id_siswa', $id)
            ->first();
    }
}