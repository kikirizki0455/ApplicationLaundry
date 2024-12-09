<?php

namespace App\Controllers\DataManagement;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;

class DataPegawai extends BaseController
{

    protected $pegawaiModel;
    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
    }
    public function index()
    {
        $builderPegawai = $this->db->table('pegawai');
        $queryPegawai = $builderPegawai->get();
        $pegawai = $queryPegawai->getResult();



        $data = [
            'title' => 'Data Pegawai | Laundry',
            'pegawai' => $pegawai
        ];
        return view('data_pegawai', $data);
    }


    public function tambah_pegawai()
    {
        $data = [
            'title' => 'Tambah Pegawai | Laundry',

        ];
        return view('tambah_pegawai', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $nomorPegawai = $data['nomor_pegawai'];

        // Cek apakah nomor pegawai sudah ada
        $existing = $this->db->table('pegawai')->where('nomor_pegawai', $nomorPegawai)->countAllResults();

        if ($existing > 0) {
            return redirect()->back()->with('error', 'Nomor pegawai sudah ada.');
        }

        // Insert tanpa ID
        $inserted = $this->pegawaiModel->insertPegawai($data);

        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('data_pegawai'))->with('success', 'Data berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function edit_pegawai($id)
    {
        $builder = $this->db->table('pegawai');
        $builder->where('id_pegawai', $id);
        $data['pegawai'] = $builder->get()->getRow();
        $data['title'] = 'Edit Pegawai | Laundry';

        return view('edit_pegawai', $data);
    }


    public function update_pegawai()
    {
        $id = $this->request->getPost('id_pegawai');
        $data = [
            'nomor_pegawai' => $this->request->getPost('nomor_pegawai'),
            'nama_pegawai' => $this->request->getPost('nama_pegawai'),
            'role_pegawai' => $this->request->getPost('role_pegawai'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password') // Password akan di-hash di model jika tidak kosong
        ];

        // Panggil model untuk memperbarui data pegawai
        if ($this->pegawaiModel->updatePegawai($id, $data)) {
            return redirect()->to(site_url('data_pegawai'))->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function delete_pegawai($id)
    {
        $this->db->table('pegawai')->delete(['id_pegawai' => $id]);

        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('data_pegawai'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(site_url('data_pegawai'))->with('error', 'Gagal menghapus data.');
        }
    }
}
