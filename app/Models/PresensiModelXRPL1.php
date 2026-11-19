<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table = 'presensi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'siswa_id',
        'tanggal',
        'status',
        'keterangan',
        'waktu'
    ];
}