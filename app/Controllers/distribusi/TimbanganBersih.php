<?php

namespace App\Controllers\Distribusi;

use App\Controllers\BaseController;
use App\Models\TimbanganBersihModel;
use App\Models\DetailTimbanganBersihModel;
use App\Models\PelipatanModel;
use App\Models\DetailPelipatanModel;
use App\Models\PegawaiModel;
use App\Models\PengirimanModel;

class TimbanganBersih extends BaseController
{
    protected $timbanganBersihModel;
    protected $detailTimbanganBersihModel;
    protected $pelipatanModel;
    protected $detailPelipatanModel;
    protected $pegawaiModel;


    public function __construct()
    {
        $this->timbanganBersihModel = new TimbanganBersihModel();
        $this->detailTimbanganBersihModel = new DetailTimbanganBersihModel();
        $this->pelipatanModel = new PelipatanModel();
        $this->detailPelipatanModel = new DetailPelipatanModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function timbangan_bersih()
    {
        $timbangan_bersih = $this->timbanganBersihModel->getTimbanganBersihWithDetails();

        $data = [
            'title' => 'Timbangan Bersih | LA-DUS',
            'timbangan_bersih' => $timbangan_bersih,

        ];

        return view('distribusi/timbangan_bersih', $data);
    }
    public function getDetailBarang($id_pelipatan)
    {
        $detailPelipatan = $this->detailPelipatanModel->getDetailByPelipatan($id_pelipatan);

        if (!$detailPelipatan) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
        }

        return $this->response->setJSON($detailPelipatan);
    }

    public function simpanBeratBersih()
    {
        $id_timbangan_bersih = $this->request->getPost('id_timbangan_bersih');
        $berat_bersih = $this->request->getPost('berat_bersih');

        $data = [
            'berat_bersih' => $berat_bersih,
            'status' => 'Selesai Timbang'
        ];

        $updated = $this->timbanganBersihModel->update($id_timbangan_bersih, $data);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Berat bersih berhasil disimpan']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Gagal menyimpan data']);
        }
    }
    public function postDetail($id)
    {
        $beratBersih = $this->request->getPost('berat_bersih');

        // Validate berat_bersih
        if (!$beratBersih) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Berat bersih tidak boleh kosong'
            ]);
        }

        log_message('debug', 'Berat bersih yang diterima: ' . $beratBersih);


        try {
            $result = $this->timbanganBersihModel->update($id, [
                'berat_bersih' => $beratBersih,
                'status' => 'pending'
            ]);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'data' => [
                        'berat_bersih' => $beratBersih,
                        'id' => $id
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat update: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }
    public function detail($id)
    {
        $timbangan_bersih = $this->timbanganBersihModel->getTimbanganBersihById($id);
        $detailBarangKotor = $this->timbanganBersihModel->getDetailBarangKotor($id);
        $detailBarang = $this->timbanganBersihModel->getDetailBarang($id);

        if (!$timbangan_bersih) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'timbananBersih' => $timbangan_bersih,
                'detailBarang' => $detailBarang,
                'detailBarangKotor' => $detailBarangKotor
            ]
        ]);
    }

    public function statMove($idTimbanganBersih)
    {
        $timbangan_bersih = $this->timbanganBersihModel->find($idTimbanganBersih);
        // Validasi keberadaan data
        if (!$timbangan_bersih) {
            return redirect()->back()->with('error', 'Data timbangan tidak ditemukan');
        }

        try {
            $this->db->transStart(); // Memulai transaksi
            // Cek apakah transaksi berhasil dimulai
            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal memulai transaksi.');
            }




            // $this->laporanModel->insert($data);

            // Simpan perubahan status timbangan bersih menjadi 'delivered'
            $inserted = $this->timbanganBersihModel->update(
                $idTimbanganBersih,
                [
                    'status' => 'process',
                    'tanggal_pengiriman' => date('Y-m-d H:i:s')
                ]
            );


            $this->db->transComplete(); // Selesaikan transaksi

            // Jika transaksi berhasil, redirect ke halaman sebelumnya dengan pesan sukses
            if ($this->db->transStatus()) {
                return redirect()->back()->with('message', 'Data pengiriman berhasil disimpan. Lakukan pengiriman segera.');
            } else {
                throw new \Exception('Transaksi gagal.');
            }
        } catch (\Throwable $th) {
            // Tangani error dan log pesan kesalahan
            $this->db->transRollback(); // Batalkan transaksi jika terjadi error
            log_message('error', 'Error: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    private function validateCSRF()
    {
        return service('security')->verify($this->request);
    }

    public function processFromPelipatan($id_pelipatan)
    {
        $pelipatan = $this->pelipatanModel->getPelipatanWithDetails($id_pelipatan);
        $detail_pelipatan = $this->detailPelipatanModel->getDetailByPelipatan($id_pelipatan);

        if (!$pelipatan || !$detail_pelipatan) {
            return redirect()->back()->with('error', 'Data pelipatan tidak ditemukan');
        }

        try {
            $this->db->transStart();

            // Insert ke timbangan bersih
            $timbangan_bersih_data = [
                'id_pelipatan' => $id_pelipatan,
                'no_invoice' => $pelipatan['no_invoice'],
                'berat_kotor' => $pelipatan['berat_barang'],
                'status' => 'pending',
                'waktu_timbang' => date('Y-m-d H:i:s'),
            ];

            $id_timbangan_bersih = $this->timbanganBersihModel->insert($timbangan_bersih_data);

            // Insert detail timbangan bersih
            foreach ($detail_pelipatan as $detail) {
                $detail_data = [
                    'id_timbangan_bersih' => $id_timbangan_bersih,
                    'id_barang' => $detail['id_barang'],
                    'jumlah_barang' => $detail['jumlah_barang'],
                    'status' => 'pending'
                ];
                $this->detailTimbanganBersihModel->insert($detail_data);
            }

            // Update status pelipatan
            $this->pelipatanModel->update($id_pelipatan, ['status' => 'moved']);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memindahkan data ke timbangan bersih');
            }

            return redirect()->to('distribusi/timbangan_bersih')->with('message', 'Data berhasil dipindahkan ke timbangan bersih');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        $rules = [
            'berat_bersih' => 'required|numeric|greater_than[0]',
            'tujuan_distribusi' => 'required',
            'catatan' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $data = [
            'berat_bersih' => $this->request->getPost('berat_bersih'),
            'tujuan_distribusi' => $this->request->getPost('tujuan_distribusi'),
            'catatan' => $this->request->getPost('catatan'),
            'status' => 'ready_delivery'
        ];

        $this->timbanganBersihModel->update($id, $data);
        return redirect()->to('distribusi/timbangan_bersih')->with('message', 'Data berhasil diupdate');
    }

    public function delivered($idTimbanganBersih)
    {
        $timbanganDone = $this->timbanganBersihModel->findAll();
        //validasi

        if (!$timbanganDone) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }


        return redirect()->back()->with('message', 'Barang sudah terkirim');
    }
}
