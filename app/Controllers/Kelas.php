<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\SiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Kelas extends BaseController
{
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index(): string
    {
        return view('kelas/index', [
            'title' => 'Data Kelas',
            'kelas' => $this->kelasModel->getWithJumlahSiswa(),
        ]);
    }

    public function tambah(): string
    {
        return view('kelas/form', [
            'title' => 'Tambah Kelas',
            'kelas' => null,
        ]);
    }

    public function simpan(): RedirectResponse
    {
        if (! $this->kelasModel->insert($this->inputData())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->kelasModel->errors());
        }

        return redirect()->to('kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('kelas/form', [
            'title' => 'Edit Kelas',
            'kelas' => $this->cari($id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->cari($id);

        if (! $this->kelasModel->update($id, $this->inputData())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->kelasModel->errors());
        }

        return redirect()->to('kelas')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function hapus(int $id): RedirectResponse
    {
        $kelas  = $this->cari($id);
        $jumlah = (new SiswaModel())->where('id_kelas', $id)->countAllResults();

        // Di database relasi siswa -> kelas memakai ON DELETE RESTRICT
        if ($jumlah > 0) {
            return redirect()->to('kelas')->with(
                'error',
                'Kelas ' . $kelas['nama_kelas'] . ' masih memiliki ' . $jumlah
                . ' siswa. Pindahkan atau hapus siswanya terlebih dahulu.'
            );
        }

        $this->kelasModel->delete($id);

        return redirect()->to('kelas')
            ->with('success', 'Kelas ' . $kelas['nama_kelas'] . ' berhasil dihapus.');
    }

    private function cari(int $id): array
    {
        $kelas = $this->kelasModel->find($id);

        if ($kelas === null) {
            throw PageNotFoundException::forPageNotFound('Kelas tidak ditemukan.');
        }

        return $kelas;
    }

    private function inputData(): array
    {
        $wali = trim((string) $this->request->getPost('wali_kelas'));

        return [
            'nama_kelas' => trim((string) $this->request->getPost('nama_kelas')),
            'wali_kelas' => $wali !== '' ? $wali : null,
        ];
    }
}