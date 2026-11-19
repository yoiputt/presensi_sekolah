<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function proses()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Login sementara
        if ($username == 'admin' && $password == '12345') {

            return redirect()->to('/dashboard');

        } else {

            return redirect()->back()->with('error', 'Username atau password salah.');

        }
    }
}