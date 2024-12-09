<?php

namespace App\Controllers\DataManagement;

use App\Controllers\BaseController;
use App\Libraries\BahanHelper;
use App\Models\BahanModel;
use Config\Services;

class DataBahan extends BaseController
{
    protected $bahanModel, $bahanHelper, $db;

    public function __construct()
    {
        $this->bahanModel = new BahanModel();

        $this->bahanHelper = new BahanHelper(
            \Config\Database::connect(),
            Services::validation()
        );
    }

    public function index()
    {
        $builderDataBahan = $this->db->table('bahan');
        $queryDataBahan = $builderDataBahan->get();
        $bahan = $queryDataBahan->getResult();

        $data = [
            'title' => 'Data Bahan | Laundry',
            'bahan' => $bahan,
        ];

        return view('data_bahan', $data);
    }

    public function tambah_bahan()
    {
        $data = [
            'title' => 'Tambah Bahan | Laundry',
        ];
        return view('tambah_bahan', $data);
    }

    public function store_bahan()
    {
        $data = $this->request->getPost();
        $data['nama_barang'] = $this->bahanHelper->normalize($data['nama_bahan']);

        $validationErrors = $this->bahanHelper->validateBahanData($data);
        if ($validationErrors !== null) {
            return redirect()->back()->withInput()->with('error_bahan', $validationErrors);
        }
        // Periksa duplikasi potensial
        if ($this->bahanHelper->checkDuplicate($data['nama_bahan'])) {
            return redirect()->back()->withInput()->with('error_bahan', 'Bahan serupa sudah ada di database.');
        }

        // Coba sisipkan barang
        try {
            $insertData = [
                'nama_bahan' => $data['nama_bahan'],
                'stok_bahan' => $data['stok_bahan']
            ];

            $this->db->table('bahan')->insert($insertData);

            if ($this->db->affectedRows() > 0) {
                return redirect()->to(site_url('data_bahan'))->with('success_bahan', 'Data bahan berhasil disimpan.');
            } else {
                return redirect()->back()->withInput()->with('error_bahan', 'Gagal menyimpan data bahan.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan penyisipan barang: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error_bahan', 'Terjadi kesalahan sistem saat menyimpan barang.');
        }
    }

    public function update_bahan()
    {
        // Ambil data dari post
        $id_bahan = $this->request->getPost('id_bahan');
        $stok = $this->request->getPost('stok_bahan');

        // Validasi input
        if (!$id_bahan || !is_array($stok) || count($stok) === 0) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        // Menghitung total stok baru dari array stok
        $totalStok = array_sum($stok);

        // Validasi apakah stok yang dihitung lebih besar atau lebih kecil dari 0
        if ($totalStok < 0) {
            return redirect()->back()->with('error', 'Stok tidak boleh negatif.');
        }

        // Siapkan data untuk update
        $data = [
            'stok_bahan' => $totalStok
        ];

        // Mulai transaksi untuk memastikan integritas data
        $this->db->transBegin();

        try {
            // Update data di tabel bahan
            $this->db->table('bahan')->where('id_bahan', $id_bahan)->update($data);

            // Cek apakah ada perubahan data
            if ($this->db->affectedRows() > 0) {
                // Commit transaksi jika berhasil
                $this->db->transCommit();
                return redirect()->to(site_url('data_bahan'))->with('success', 'Data berhasil diperbarui');
            } else {
                // Jika tidak ada perubahan
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Gagal memperbarui data atau tidak ada perubahan.');
            }
        } catch (\Exception $e) {
            // Rollback jika ada error
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function edit_bahan($id_bahan)
    {
        $builder = $this->db->table('bahan');
        $builder->where('id_bahan', $id_bahan);
        $data['bahan'] = $builder->get()->getRow();
        $data['title'] = 'Edit Bahan | Laundry';

        return view('edit_bahan', $data);
    }

    public function delete_bahan($id_bahan)
    {

        $this->db->table('bahan')->delete(['id_bahan' => $id_bahan]);

        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('data_bahan'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(site_url('data_bahan'))->with('error', 'Gagal menghapus data.');
        }
    }

    public function getBahanData($id_bahan)
    {

        $bahanUpdate = $this->bahanModel->find($id_bahan);

        if ($bahanUpdate) {
            return $this->response->setJSON([
                'success' => true,
                'nama_bahan' => $bahanUpdate['nama_bahan']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data bahan tidak ditemukan'
            ]);
        }
    }
}
