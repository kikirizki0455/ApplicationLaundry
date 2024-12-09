<?php

namespace App\Controllers\Pengelolaan;

use App\Controllers\BaseController;
use App\Models\PelipatanModel;
use App\Models\DetailPelipatanModel;
use App\Models\BarangModel;
use App\Models\PenyetrikaanModel;
use App\Models\TimbanganBersihModel;

class Pelipatan extends BaseController
{
    protected $pelipatanModel;
    protected $detailPelipatanModel;
    protected $barangModel;
    protected $penyetrikaanModel;
    protected $timbanganBersihModel;
    public function __construct()
    {
        $this->pelipatanModel = new PelipatanModel();
        $this->detailPelipatanModel = new DetailPelipatanModel();
        $this->barangModel = new BarangModel();
        $this->penyetrikaanModel = new PenyetrikaanModel();
        $this->timbanganBersihModel = new TimbanganBersihModel();
    }

    public function index()
    {
        $pelipatan = $this->pelipatanModel->getPelipatanDetails();

        $data = [
            'title' => 'Pelipatan | LA-DUS',
            'pelipatan' => $pelipatan
        ];

        return view('pengelola/pelipatan', $data);
    }


    public function detail($id_pelipatan)
    {
        $pelipatan = $this->pelipatanModel->find($id_pelipatan);
        $result = $this->detailPelipatanModel->getDetailByPelipatan($id_pelipatan);
        $detail_pelipatan = $result['detail_pelipatan'];

        $timbangan_barang = $result['timbangan_barang'];
        $barang = $this->barangModel->findAll();
        $data = [
            'title' => 'Detail | LA-DUS',
            'pelipatan' => $pelipatan,
            'detail_pelipatan' => $detail_pelipatan,
            'timbangan_barang' => $timbangan_barang,
            'barang' => $barang
        ];

        return view('pengelola/detail', $data);
    }

    public function addDetail($id_pelipatan)
    {
        $rules = [
            'id_barang' => 'required',
            'jumlah_barang' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $id_barang = $this->request->getPost('id_barang');
        $jumlah_barang = $this->request->getPost('jumlah_barang');

        // Cek stok barang
        $barang = $this->barangModel->find($id_barang);
        if ($jumlah_barang > $barang['stok']) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia');
        }

        $data = [
            'id_pelipatan' => $id_pelipatan,
            'id_barang' => $id_barang,
            'jumlah_barang' => $jumlah_barang,
            'status' => 'pending'
        ];

        $this->detailPelipatanModel->insert($data);
        return redirect()->to('pengelolaan/pelipatan/detail/' . $id_pelipatan)->with('message', 'Detail barang berhasil ditambahkan');
    }

    public function start($id_pelipatan)
    {


        $this->pelipatanModel->update($id_pelipatan, [
            'status' => 'in_progress',
            'tanggal_mulai' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('pengelolaan/pelipatan')->with('message', 'Proses pelipatan dimulai');
    }

    public function statMove($id_pelipatan)
    {
        $details = $this->detailPelipatanModel->where('id_pelipatan', $id_pelipatan)->findAll();
        if (empty($details)) {
            return redirect()->back()->with('error-barang', 'Tambahkan detail barang terlebih dahulu');
        }

        $this->pelipatanModel->update($id_pelipatan, [
            'status' => 'ready_move',
            'tanggal_selesai' => date('Y-m-d H:i:s')
        ]);

        // Update status semua detail menjadi completed
        $this->detailPelipatanModel->where('id_pelipatan', $id_pelipatan)
            ->set(['status' => 'ready_move'])
            ->update();

        return redirect()->to('pengelolaan/pelipatan')->with('message', 'Proses pelipatan selesai');
    }

    public function delete_detail($id)
    {
        $detail = $this->detailPelipatanModel->find($id);
        if (!$detail) {
            return redirect()->back()->with('error', 'Detail tidak ditemukan');
        }

        try {
            $this->detailPelipatanModel->delete($id);
            return redirect()->back()->with('messageHapus', 'Detail berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus detail');
        }
    }

    //memindahkan pelipatan ke timbangan bersih
    public function move($id_pelipatan)
    {
        // Cari data pelipatan
        $pelipatan = $this->pelipatanModel->find($id_pelipatan);

        // Validasi keberadaan data
        if (!$pelipatan) {
            return redirect()->back()->with('error', 'Data Pelipatan tidak ditemukan');
        }

        try {
            // Mulai transaksi database
            $this->db->transStart();

            // Ambil detail pelipatan
            $barangDetail = $this->detailPelipatanModel->getDetailByPelipatan($id_pelipatan);
            $timbanganBarang = $barangDetail['timbangan_barang'];

            // Validasi barang detail
            if (empty($barangDetail)) {
                throw new \Exception('Tidak ada barang yang terkait dengan pelipatan ini.');
            }

            // Kurangi stok dicuci dan kembalikan ke ruangan barang untuk setiap barang di detail pelipatan
            foreach ($timbanganBarang as $barang) {

                // Pastikan konversi tipe data benar
                $id_ruangan = isset($barang['id_ruangan']) ? (int)$barang['id_ruangan'] : null;
                $id_barang = isset($barang['id_barang']) ? (int)$barang['id_barang'] : null;
                $jumlah = isset($barang['jumlah']) ? (int)$barang['jumlah'] : 0;


                // Validasi data
                if ($id_ruangan === null || $id_barang === null || $jumlah === 0) {
                    log_message('error', 'Data barang tidak lengkap: ' . json_encode($barang));
                    continue;
                }

                // Cek apakah record ruangan_barang sudah ada
                $existingRuanganBarang = $this->db->table('ruangan_barang')
                    ->where('id_ruangan', $id_ruangan)
                    ->where('id_barang', $id_barang)
                    ->get()
                    ->getRow();

                if ($existingRuanganBarang) {
                    // Update jumlah jika record sudah ada
                    $updateStokRuangan = $this->db->table('ruangan_barang')
                        ->where('id_ruangan', $id_ruangan)
                        ->where('id_barang', $id_barang)
                        ->set('jumlah', 'jumlah + ' . $jumlah, false)
                        ->update();

                    if (!$updateStokRuangan) {
                        throw new \Exception("Gagal update stok ruangan untuk barang ID {$id_barang} di ruangan {$id_ruangan}");
                    }
                } else {
                    // Insert record baru jika belum ada
                    $insertRuanganBarang = $this->db->table('ruangan_barang')->insert([
                        'id_ruangan' => $id_ruangan,
                        'id_barang' => $id_barang,
                        'jumlah' => $jumlah
                    ]);

                    if (!$insertRuanganBarang) {
                        throw new \Exception("Gagal insert stok ruangan untuk barang ID {$id_barang} di ruangan {$id_ruangan}");
                    }
                }

                // Update stok dicuci
                $updateStok = $this->db->table('barang')
                    ->where('id_barang', $id_barang)
                    ->set('stok_dicuci', 'stok_dicuci - ' . $jumlah, false)
                    ->update();

                // Validasi apakah update berhasil
                if (!$updateStok) {
                    throw new \Exception('Gagal mengupdate stok barang dengan ID ' . $id_barang);
                }
            }

            // Data untuk timbangan bersih
            $data = [
                'id_pelipatan' => $id_pelipatan,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert ke timbangan bersih
            $this->timbanganBersihModel->insert($data);

            // Update status pelipatan
            $inserted = $this->pelipatanModel->update($id_pelipatan, [
                'status' => 'completed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Selesaikan transaksi
            $this->db->transComplete();

            // Cek status transaksi
            if ($this->db->transStatus() === false) {
                // Jika gagal
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan saat memindahkan data');
            }

            // Jika berhasil
            return redirect()->to('pengelolaan/pelipatan')
                ->with('message', 'Data berhasil dipindahkan');
        } catch (\Throwable $e) {
            // Log error
            log_message('error', 'Error in move method: ' . $e->getMessage());

            // Batalkan transaksi jika terjadi kesalahan
            $this->db->transRollback();

            return redirect()->back()
                ->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }
}
