<?php

namespace App\Controllers;

use App\Models\SiswaModel;

class Siswa extends BaseController
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $data['siswa'] = $this->siswaModel->findAll();

        return view('siswa', $data);
    }

    public function hapus($id)
    {
        $this->siswaModel->delete($id);

        return redirect()->to(site_url('siswa'));
    }
    public function tambah()
{
    $this->siswaModel->insert([
        'nis' => $this->request->getPost('nis'),
        'nama' => $this->request->getPost('nama'),
        'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        'kelas' => $this->request->getPost('kelas'),
        'alamat' => $this->request->getPost('alamat')
    ]);

    return redirect()->to(site_url('siswa'));
}
}