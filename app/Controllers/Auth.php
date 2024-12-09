<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;

class Auth extends BaseController
{
    protected $pegawaiModel;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        // Menggunakan helper session()
        if (session('logged_in')) {
            return redirect()->to($this->getDashboardByRole());
        }
        $data = [
            'title' => 'LOGIN || LA-DUS',
        ];
        return view('auth/login', $data);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $pegawai = $this->pegawaiModel->getPegawaiByUsername($username);

        if ($pegawai && password_verify($password, $pegawai['password'])) {
            // Set session menggunakan helper session()
            session()->set([
                'user_id' => $pegawai['id_pegawai'],
                'username' => $pegawai['username'],
                'role' => $pegawai['role_pegawai'],
                'nama' => $pegawai['nama_pegawai'],
                'logged_in' => true
            ]);
            session()->setFlashdata('welcome_message', true);
            return redirect()->to($this->getDashboardByRole());
        } else {
            // Set flashdata menggunakan helper session()
            session()->setFlashdata('error', 'Email atau Password salah!');
            return redirect()->to('auth');
        }
    }

    private function getDashboardByRole()
    {
        // Menggunakan helper session() untuk mendapatkan role
        switch (session('role')) {
            case 'admin':
                return site_url('dashboard');
            case 'pengelola':
                return site_url('pengelolaan/pengelola');
            case 'distribusi':
                return site_url('distribusi');
            default:
                return site_url('auth');
        }
    }

    public function logout()
    {
        // Destroy session menggunakan helper session()
        session()->destroy();
        return redirect()->to('auth');
    }
    public function processLogin($userData)
    {
        // Proses validasi login
        // Jika berhasil:
        session()->set('id_pegawai', $userData['id_pegawai']);
    }
}
