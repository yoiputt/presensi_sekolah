<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id_tahun';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tahun_ajaran', 'semester', 'aktif'];

    /**
     * Tahun ajaran yang sedang aktif.
     */
    public function getAktif(): ?array
    {
        return $this->where('aktif', 1)->first();
    }
}