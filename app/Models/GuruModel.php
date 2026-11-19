<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'guru';
    protected $primaryKey       = 'id_guru';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nip', 'nama_guru', 'username', 'password'];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash password otomatis jika masih berupa teks biasa.
     */
    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password'])) {
            return $data;
        }

        $password = $data['data']['password'];

        if (password_get_info($password)['algo'] === null) {
            $data['data']['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Verifikasi login. Mengembalikan data guru (tanpa password) atau null.
     *
     * Data awal di database masih memakai password teks biasa (admin123).
     * Jika ditemukan, otomatis di-hash saat login pertama berhasil.
     */
    public function attempt(string $username, string $password): ?array
    {
        $guru = $this->where('username', $username)->first();

        if (! $guru) {
            return null;
        }

        $stored = $guru['password'];

        if (password_get_info($stored)['algo'] === null) {
            // Password lama (plain text)
            if (! hash_equals($stored, $password)) {
                return null;
            }

            // Upgrade ke hash (hook hashPassword yang meng-hash)
            $this->update($guru['id_guru'], ['password' => $password]);
        } elseif (! password_verify($password, $stored)) {
            return null;
        }

        unset($guru['password']);

        return $guru;
    }
}