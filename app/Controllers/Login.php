<?php

namespace App\Controllers;

use App\Models\GuruModel;
use CodeIgniter\HTTP\RedirectResponse;

class Login extends BaseController
{
    public function index(): string|RedirectResponse
    {
        // Jika sudah login, langsung ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('login', ['title' => 'Login']);
    }

    public function proses(): RedirectResponse
    {
        $rules = [
            'username' => 'required|max_length[50]',
            'password' => 'required',
        ];

        $messages = [
            'username' => ['required' => 'Username wajib diisi.'],
            'password' => ['required' => 'Password wajib diisi.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $guru = (new GuruModel())->attempt($username, $password);

        if ($guru === null) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Cegah session fixation
        session()->regenerate(true);

        session()->set([
            'logged_in' => true,
            'id_guru'   => (int) $guru['id_guru'],
            'nama_guru' => $guru['nama_guru'],
            'username'  => $guru['username'],
        ]);

        return redirect()->to('/dashboard');
    }
}