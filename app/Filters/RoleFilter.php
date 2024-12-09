<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Jika tidak ada session login, redirect ke halaman login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userRole = $session->get('role');
        $currentURL = current_url();

        // Cek akses berdasarkan role

        // distribusi
        if ($userRole === 'distribusi' && strpos($currentURL, 'pengelola') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        if ($userRole === 'distribusi' && strpos($currentURL, 'dashboard') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        if ($userRole === 'distribusi' && strpos($currentURL, 'laporan/laporan') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        if ($userRole === 'distribusi' && strpos($currentURL, 'data_pegawai') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'distribusi' && strpos($currentURL, 'data_barang') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'distribusi' && strpos($currentURL, 'data_bahan') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'distribusi' && strpos($currentURL, 'data_mesin') !== false) {
            return redirect()->to('/distribusi')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }


        //pengelolaan

        if ($userRole === 'pengelola' && strpos($currentURL, 'distribusi') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'dashboard') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'laporan/laporan') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'data_pegawai') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'data_barang') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'data_bahan') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
        if ($userRole === 'pengelola' && strpos($currentURL, 'data_mesin') !== false) {
            return redirect()->to('/pengelolaan/pengelola')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something after the execution of routes
    }
}
