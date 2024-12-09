<?php

namespace App\Controllers\DataManagement;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Libraries\BarangHelper;
use Config\Services;

class DataBarang extends BaseController
{

    protected $barangModel, $barangHelper, $db;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->barangHelper = new BarangHelper(
            \Config\Database::connect(),
            Services::validation()
        );
    }


    public function index()
    {

        $barang = $this->barangModel->getBarangWithStokDicuci();
        $data = [
            'title' => 'Data Barang | Laundry',
            'barang' => $barang,
        ];

        return view('data_barang', $data);
    }

    public function tambah_barang()
    {
        $data = [
            'title' => 'Tambah Barang | Laundry',
        ];
        return view('tambah_barang', $data);
    }

    public function update_barang()
    {
        $id_barang = $this->request->getPost('id_barang');
        $stok = $this->request->getPost('stok');


        // Validasi input
        if (!$id_barang || !is_array($stok) || count($stok) === 0) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        // Menghitung total stok baru dari array stok
        $totalStok = array_sum($stok);

        // Siapkan data untuk update
        $data = [
            'stok' => $totalStok
        ];

        // Update data di tabel barang
        $this->db->table('barang')->where('id_barang', $id_barang)->update($data);

        // Cek apakah ada perubahan data
        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('data_barang'))->with('success', 'Data berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data atau tidak ada perubahan.');
        }
    }


    public function store_barang()
    {
        $data = $this->request->getPost();

        // Normalisasi nama barang
        $data['nama_barang'] = $this->barangHelper->normalize($data['nama_barang']);

        // Validasi data barang
        $validationErrors = $this->barangHelper->validateBarangData($data);

        if ($validationErrors !== null) {
            return redirect()->back()->withInput()->with('errors', $validationErrors);
        }

        // Periksa duplikasi potensial
        if ($this->barangHelper->checkDuplicate($data['nama_barang'])) {
            return redirect()->back()->withInput()->with('error_barang', 'Barang serupa sudah ada di database.');
        }

        // Coba sisipkan barang
        try {
            $insertData = [
                'nama_barang' => $data['nama_barang'],
                'stok' => $data['stok']
            ];

            $this->db->table('barang')->insert($insertData);

            if ($this->db->affectedRows() > 0) {
                return redirect()->to(site_url('data_barang'))->with('success_barang', 'Data barang berhasil disimpan.');
            } else {
                return redirect()->back()->withInput()->with('error_barang', 'Gagal menyimpan data barang.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan penyisipan barang: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error_barang', 'Terjadi kesalahan sistem saat menyimpan barang.');
        }
    }



    public function delete_barang($id_barang)
    {
        $this->db->table('barang')->delete(['id_barang' => $id_barang]);

        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('data_barang'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(site_url('data_barang'))->with('error', 'Gagal menghapus data.');
        }
    }
    public function edit_barang($id_barang)
    {
        $builder = $this->db->table('barang');
        $builder->where('id_barang', $id_barang);
        $data['barang'] = $builder->get()->getRow();
        $data['title'] = 'Edit Pegawai | Laundry';

        return view('edit_barang', $data);
    }

    public function getDataBarang($id)
    {
        $barangUpdate = $this->barangModel->find($id);
        if ($barangUpdate) {
            return $this->response->setJSON([
                'success' => true,
                'nama_barang' => $barangUpdate['nama_barang']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }
}
