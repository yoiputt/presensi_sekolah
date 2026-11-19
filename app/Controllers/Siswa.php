<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\SiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Siswa extends BaseController
{
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    public function index(): string
    {
        $idKelas = (int) $this->request->getGet('kelas');
        $q       = $this->request->getGet('q');
        $keyword = is_string($q) ? trim($q) : '';

        return view('siswa/index', [
            'title'   => 'Data Siswa',
            'siswa'   => $this->siswaModel->getWithKelas($idKelas ?: null, $keyword),
            'kelas'   => $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll(),
            'idKelas' => $idKelas,
            'keyword' => $keyword,
        ]);
    }

    public function tambah(): string
    {
        return view('siswa/form', [
            'title' => 'Tambah Siswa',
            'siswa' => null,
            'kelas' => $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll(),
        ]);
    }

    public function simpan(): RedirectResponse
    {
        if (! $this->siswaModel->insert($this->inputData())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->siswaModel->errors());
        }

        return redirect()->to('siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('siswa/form', [
            'title' => 'Edit Siswa',
            'siswa' => $this->cari($id),
            'kelas' => $this->kelasModel->orderBy('nama_kelas', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->cari($id);

        $data             = $this->inputData();
        $data['id_siswa'] = $id; // dipakai aturan is_unique agar mengabaikan baris sendiri

        if (! $this->siswaModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->siswaModel->errors());
        }

        return redirect()->to('siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function hapus(int $id): RedirectResponse
    {
        $siswa = $this->cari($id);

        // Presensi milik siswa ikut terhapus (ON DELETE CASCADE di database)
        $this->siswaModel->delete($id);

        return redirect()->to('siswa')
            ->with('success', 'Siswa ' . $siswa['nama_siswa'] . ' berhasil dihapus.');
    }

    private function cari(int $id): array
    {
        $siswa = $this->siswaModel->findWithKelas($id);

        if ($siswa === null) {
            throw PageNotFoundException::forPageNotFound('Siswa tidak ditemukan.');
        }

        return $siswa;
    }

    private function inputData(): array
    {
        return [
            'nis'           => trim((string) $this->request->getPost('nis')),
            'nama_siswa'    => trim((string) $this->request->getPost('nama_siswa')),
            'id_kelas'      => $this->request->getPost('id_kelas'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ];
    }
}