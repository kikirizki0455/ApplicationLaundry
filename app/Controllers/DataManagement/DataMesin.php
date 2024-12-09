<?php

namespace App\Controllers\DataManagement;

use App\Controllers\BaseController;
use App\Models\MesinCuciModel;
use App\Models\BahanModel;
use App\Models\MesinBahanModel;
use App\Libraries\MesinHelper;
use Config\Services;


class DataMesin extends BaseController
{
    protected $mesinModel, $bahanModel, $mesinBahanModel, $mesinHelper;

    public function __construct()
    {
        $this->mesinModel = new MesinCuciModel();
        $this->bahanModel = new BahanModel();
        $this->mesinBahanModel = new MesinBahanModel();
        $this->mesinHelper = new MesinHelper(
            \Config\Database::connect(),
            Services::validation()
        );
    }

    public function dataMesin()
    {
        $mesin = $this->mesinModel->getMesin();
        $bahan  = $this->bahanModel->getAllBahan();

        $data = [
            'title' => 'Mesin | Laundry',
            'mesin' => $mesin,
            'bahan' => $bahan,
        ];

        return view('data_mesin', $data);
    }
    public function tambahMesin()
    {
        $data = [
            'title' => 'Tambah Mesin | Laundry',

        ];
        return view('tambah_mesin', $data);
    }

    public function storeMesin()
    {
        $data = $this->request->getPost();

        // Normalisasi nama barang
        $data['nama_mesin'] = $this->mesinHelper->normalize($data['nama_mesin']);

        // Validasi data barang
        $validationErrors = $this->mesinHelper->validateMesinData($data);

        if ($validationErrors !== null) {
            return redirect()->back()->withInput()->with('error', $validationErrors);
        }

        // Periksa duplikasi potensial
        if ($this->mesinHelper->checkDuplicate($data['nama_mesin'])) {
            return redirect()->back()->withInput()->with('error', 'Mesin serupa sudah ada di database.');
        }

        // Coba sisipkan barang
        try {
            $insertData = [
                'nama_mesin' => $data['nama_mesin'],
                'kapasitas' => $data['kapasitas']
            ];

            $this->db->table('mesin_cuci')->insert($insertData);

            if ($this->db->affectedRows() > 0) {
                return redirect()->to(site_url('data_mesin'))->with('success', 'Data berhasil disimpan.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data .');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan penyisipan barang: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    // Tambahkan method untuk mengambil opsi bahan
    public function getBahanOptions()
    {
        $bahan = $this->bahanModel->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $bahan
        ]);
    }

    // Tambahkan method untuk menyimpan bahan
    public function storeBahan()
    {
        $id_mesin = $this->request->getPost('id_mesin');
        $id_bahan = $this->request->getPost('id_bahan');
        $jumlah = $this->request->getPost('jumlah');

        // Validasi id_mesin
        $mesinExists = $this->mesinModel->find($id_mesin);
        if (!$mesinExists) {
            return redirect()->back()->with('error', 'Data mesin tidak ditemukan.');
        }

        try {
            // Begin Transaction
            $this->db->transBegin();

            // Loop through arrays
            for ($i = 0; $i < count($id_bahan); $i++) {
                // Skip if empty values
                if (empty($id_bahan[$i]) || empty($jumlah[$i])) {
                    continue;
                }

                // Check if combination already exists
                $existing = $this->mesinBahanModel->where([
                    'id_mesin' => $id_mesin,
                    'id_bahan' => $id_bahan[$i]
                ])->first();

                if ($existing) {
                    // Update existing record
                    $this->mesinBahanModel->update($existing['id'], [
                        'jumlah_bahan' => $jumlah[$i]
                    ]);
                } else {
                    // Insert new record
                    $data = [
                        'id_mesin' => $id_mesin,
                        'id_bahan' => $id_bahan[$i],
                        'jumlah_bahan' => $jumlah[$i]
                    ];
                    $this->mesinBahanModel->insert($data);
                }
            }

            // Commit if all successful
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Gagal menyimpan data bahan.');
            }

            $this->db->transCommit();
            return redirect()->to('data_mesin')->with('success', 'Bahan berhasil ditambahkan.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal menambahkan bahan: ' . $e->getMessage());
        }
    }

    // Tambahkan method untuk mengambil bahan yang sudah ada
    public function getExistingBahan($id_mesin)
    {
        $bahan = $this->mesinBahanModel->getBahanByMesin($id_mesin);
        return $this->response->setJSON([
            'success' => true,
            'data' => $bahan
        ]);
    }

    public function deleteMesin($id_mesin)
    {
        // ambil data berdasarkan id_mesin
        $mesinDelete = $this->mesinModel->find($id_mesin);

        if (!$mesinDelete) {
            return redirect()->to('/data_mesin')->with('error', 'Data mesin tidak ada di database');
        }

        if ($this->mesinModel->delete($id_mesin)) {
            return redirect()->to('data_mesin')->with('success', 'Data mesin berhasil dihapus.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Data mesin tidak berhasil di hapus');
        }
    }
    public function updateStatus($id)
    {
        // Validasi input
        $newStatus = $this->request->getPost('new_status');
        if (!in_array($newStatus, ['aktif', 'tidak_aktif'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Update status
        $this->mesinModel->update($id, ['status' => $newStatus]);

        // Set pesan sukses
        $message = $newStatus === 'aktif' ? 'Mesin berhasil diaktifkan' : 'Mesin berhasil dinonaktifkan';
        return redirect()->back()->with('success', $message);
    }
}
