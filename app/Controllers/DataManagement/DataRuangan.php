<?php

namespace App\Controllers\DataManagement;

use App\Controllers\BaseController;
use App\Models\RuanganModel;
use App\Models\BarangModel;
use App\Models\RuanganBarangModel;
use App\Libraries\RuanganHelper;
use CodeIgniter\Commands\Server\Serve;
use Config\Services;

class DataRuangan extends BaseController
{
    protected $ruanganModel;
    protected $barangModel;
    protected $ruanganBarangModel;
    protected $ruanganHelper;

    public function __construct()
    {
        $this->ruanganModel = new RuanganModel();
        $this->barangModel = new BarangModel();
        $this->ruanganBarangModel = new RuanganBarangModel();
        $this->ruanganHelper = new RuanganHelper(
            \config\Database::connect(),
            Services::validation()
        );
    }

    public function index()
    {
        $ruangan = $this->ruanganModel->getBarang();

        $barang = $this->barangModel->findAll();
        $data = [
            'title' => 'Ruangan || LA-DUS',
            'ruangan' => $ruangan,
            'barang' => $barang
        ];


        return view('data_ruangan', $data);
    }

    public function tambahRuangan()
    {
        $data = [
            'title' => 'Tambah Ruangan | Laundry',
        ];
        return view('tambah_ruangan', $data);
    }

    public function create()
    {
        $ruangan = $this->request->getPost('nama_ruangan');

        //normalisasi nama ruangan
        $data['nama_ruangan'] = $this->ruanganHelper->normalize($ruangan);
        //validasi data ruangan
        $validationErrors = $this->ruanganHelper->validateRuanganData($data);
        if ($validationErrors !== null) {
            return redirect()->back()->withInput()->with('error', $validationErrors);
        }

        //periksa duplikasi
        if ($this->ruanganHelper->checkDuplicate($data['nama_ruangan'])) {
            return redirect()->back()->withInput()->with('error', 'ruangan sudah ada di database');
        }

        try {
            $insertData = [
                'nama_ruangan' => $data['nama_ruangan'],  // Pastikan ini array asosiatif
            ];
            $this->db->table('ruangan')->insert($insertData);

            if ($this->db->affectedRows() > 0) {
                return redirect()->to(site_url('data_ruangan'))->with('success', 'Data ruangan berhasil disimpan');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data');
            }
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan terjadi: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan ruangan.');
        }



        $this->db->table('ruangan')->insert(['nama_ruangan' => $namaRuangan]);


        return redirect('data_ruangan');
    }

    public function storeRuangan()
    {
        // Ambil data dari form
        $id_ruangan = $this->request->getPost('id_ruangan');
        $id_barang = $this->request->getPost('id_barang');
        $jumlah = $this->request->getPost('jumlah'); // pastikan ini adalah array yang berisi jumlah barang

        // Validasi input
        if (!$id_ruangan || !is_array($id_barang) || !is_array($jumlah)) {
            return redirect()->back()->with('error', 'Data barang tidak valid.');
        }

        // Cek apakah ruangan ada di database
        $ruanganExist = $this->ruanganModel->find($id_ruangan);
        if (!$ruanganExist) {
            return redirect()->back()->with('error', 'Data ruangan tidak ditemukan.');
        }

        try {
            $this->db->transStart(); // Mulai transaksi

            foreach ($id_barang as $index => $barang_id) {
                // Skip jika barang atau jumlah kosong atau tidak valid
                if (empty($barang_id) || !isset($jumlah[$index]) || $jumlah[$index] <= 0) {
                    continue;
                }

                // Validasi barang ada di database
                $barangExist = $this->barangModel->find($barang_id);
                if (!$barangExist) {
                    continue; // Skip barang yang tidak ada
                }

                // Ambil stok barang dari master barang
                $stokMaster = $this->barangModel->select('stok')->where('id_barang', $barang_id)->first();

                if (!$stokMaster) {
                    return redirect()->back()->with('error', 'Barang tidak ditemukan di master barang atau stok tidak ada.');
                }
                // Menghitung jumlah barang yang sudah ada di ruangan
                $stokBarangDiRuanganIni  = $this->ruanganBarangModel->sum($id_ruangan, $barang_id);
                // Jika hasilnya tidak null, ambil nilai jumlah, jika null set ke 0
                $stokBarangDiRuanganIni = $stokBarangDiRuanganIni ? $stokBarangDiRuanganIni['jumlah'] : 0;

                // Menghitung jumlah barang yang sudah ada di semua ruangan
                $stokBarangDiRuangan = $this->ruanganBarangModel->sumBarang($barang_id);
                // Jika hasilnya tidak null, ambil nilai jumlah, jika null set ke 0
                $stokBarangDiRuangan = $stokBarangDiRuangan ? $stokBarangDiRuangan['jumlah'] : 0;



                // Jika jumlah barang yang akan dimasukkan ke ruangan + stok yang ada di ruangan melebihi stok master
                if (($stokBarangDiRuangan + $jumlah[$index]) > $stokMaster['stok']) {
                    return redirect()->back()->with('error', 'Jumlah barang melebihi stok yang tersedia di master barang.');
                }

                // Cek apakah barang sudah ada di ruangan
                $existingRecord = $this->ruanganBarangModel->where([
                    'id_ruangan' => $id_ruangan,
                    'id_barang' => $barang_id
                ])->first();

                if ($existingRecord) {
                    // Jika sudah ada, update jumlah barang
                    $this->ruanganBarangModel->update($existingRecord['id_ruangan_barang'], [
                        'jumlah' => $existingRecord['jumlah'] + $jumlah[$index]
                    ]);
                } else {
                    // Jika belum ada, insert data baru
                    $this->ruanganBarangModel->insert([
                        'id_ruangan' => $id_ruangan,
                        'id_barang' => $barang_id,
                        'jumlah' => $jumlah[$index]
                    ]);
                }
            }

            $this->db->transComplete(); // Selesaikan transaksi

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menyimpan data.');
            }

            return redirect()->to('data_ruangan')->with('success', 'Barang berhasil ditambahkan.');
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }




    public function getExistingBarang($idRuangan)
    {
        try {
            $existingBarang = $this->ruanganBarangModel
                ->select('barang.nama_barang, ruangan_barang.jumlah')
                ->join('barang', 'barang.id_barang = ruangan_barang.id_barang')
                ->where('ruangan_barang.id_ruangan', $idRuangan)
                ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $existingBarang
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getBarangOptions()
    {
        try {
            $barang = $this->barangModel->findAll();
            return $this->response->setJSON([
                'success' => true,
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function updateStatus($idRuangan)
    {
        $newStatus = $this->request->getPost('new_status');
        $this->ruanganModel->update($idRuangan, ['status' => $newStatus]);
        return redirect()->to('ruangan')->with('success', 'Status ruangan berhasil diubah');
    }

    public function delete($idRuangan)
    {
        $this->ruanganModel->delete($idRuangan);
        return redirect()->to('data_ruangan')->with('success', 'Ruangan berhasil dihapus');
    }
}
