<?php

namespace App\Controllers\Pengelolaan;

use App\Controllers\BaseController;
use App\Models\TimbanganModel;
use App\Models\BahanModel;
use App\Models\MesinCuciModel;
use App\Models\PencucianModel;
use App\Models\PengeringanModel;
use App\Models\PegawaiModel;

class Pencucian extends BaseController
{
    // Deklarasi properti
    protected $pencucianModel, $bahanModel, $timbanganModel, $pencucianBahanModel, $pengeringanModel, $mesinCuciModel, $pegawaiModel;

    public function __construct()
    {
        // Inisialisasi model Pencucian dan Bahan
        $this->pencucianModel = new PencucianModel();
        $this->bahanModel = new BahanModel();
        $this->timbanganModel = new TimbanganModel();
        $this->pengeringanModel = new PengeringanModel();
        $this->mesinCuciModel = new MesinCuciModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $pencucian = $this->pencucianModel->getInProgressPencucian();

        $data = [
            'title' => 'Pencucian | Laundry',
            'pencucian' => $pencucian,
        ];


        return view('pengelola/pencucian_list', $data);
    }
    public function store()
    {
        $idTimbangan = $this->request->getPost('id_timbangan');

        // Ambil berat_barang dari tabel timbangan
        $timbangan = $this->pencucianModel->db->table('timbangan')
            ->select('berat_barang')
            ->where('id_timbangan', $idTimbangan)
            ->get()
            ->getRow();

        // Ketika data pertama kali masuk, status pending tanpa tanggal mulai
        $this->pencucianModel->save([
            'id_timbangan' => $idTimbangan,
            'berat_barang' => $timbangan ? $timbangan->berat_barang : null,
            'pencucian_status' => 'pending',
            'tanggal_mulai' => null, // Tanggal mulai dikosongkan
            'tanggal_selesai' => null // Tanggal selesai dikosongkan
        ]);

        // Mengupdate status timbangan
        $this->pencucianModel->db->table('timbangan')
            ->where('id_timbangan', $idTimbangan)
            ->update(['status' => 'in_progress']);

        return redirect()->to('/pengelolaan/pencucian')->with('message', 'Pencucian berhasil ditambahkan.');
    }

    public function start()
    {

        $id_cuci = $this->request->getPost('id_cuci');
        if ($id_cuci) {
            // Set tanggal mulai saat tombol mulai ditekan
            $this->pencucianModel->update($id_cuci, [
                'pencucian_status' => 'in_progress',
                'tanggal_mulai' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to('/pengelolaan/pencucian')->with('message', 'Pencucian telah dimulai.');
        }
        return redirect()->back()->with('error', 'ID pencucian tidak ditemukan.');
    }


    public function update($id_cuci)
    {
        // Mengupdate data pencucian
        $this->pencucianModel->update($id_cuci, [
            'tanggal_selesai' => date('Y-m-d H:i:s'),
        ]);

        $pencucian = $this->pencucianModel->find($id_cuci);
        if ($pencucian) {
            $this->pencucianModel->db->table('timbangan')
                ->where('id_timbangan', $pencucian['id_timbangan'])
                ->update(['status' => 'in_progress']);
        }

        return redirect()->to('/pengelolaan/pencucian')->with('message', 'Pencucian berhasil diperbarui.');
    }

    public function StatMove($id_cuci)
    {
        try {
            // Ambil data pencucian yang ada
            $existingData = $this->pencucianModel->find($id_cuci);

            if (!$existingData) {
                throw new \Exception('Data pencucian tidak ditemukan');
            }

            // Update hanya status dan tanggal selesai, pertahankan tanggal mulai yang ada
            $updateData = [
                'pencucian_status' => 'ready_move',
                'tanggal_selesai' => date('Y-m-d H:i:s')
            ];

            $this->pencucianModel->update($id_cuci, $updateData);

            return redirect()->to('/pengelolaan/pencucian')->with('message', 'Pencucian telah selesai.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    //tambah bahan

    public function getBahan()
    {
        $bahan = $this->bahanModel->getAllBahan();
        // Jika $bahan bukan array, maka var_dump untuk melihat kembalian dari getAllBahan
        return $this->response->setJSON($bahan);
    }

    public function tambahBahan($id_cuci)
    {

        // Ambil data bahan menggunakan model
        $bahan = $this->bahanModel->findAll(); // Mengambil semua data bahan

        // Ambil data pencucian berdasarkan id_cuci
        $timbangan = $this->pencucianModel->where('id_cuci', $id_cuci)->first();
        // Ambil id_timbangan dari hasil query pencucian
        $id_timbangan = isset($timbangan['id_timbangan']) ? $timbangan['id_timbangan'] : null;

        // Siapkan data untuk view
        $data = [
            'title' => 'Tambah Bahan | Laundry',
            'bahan' => $bahan,
            'id_cuci' => $id_cuci, // ID pencucian
            'id_timbangan' => $id_timbangan // Mengirim id_timbangan ke view
        ];

        // Simpan data ke database
        return view('pengelola/tambah_bahan', $data);
    }

    public function storeBahan()
    {

        // Validasi data input
        $validation = $this->validate([
            'id_bahan.*' => 'required',
            'jumlah.*' => 'required|numeric|greater_than[0]'
        ]);

        if (!$validation) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil data dari form
        $idCuci = $this->request->getPost('id_cuci');
        $idBahan = $this->request->getPost('id_bahan');
        $jumlah = $this->request->getPost('jumlah');

        foreach ($idBahan as $key => $bahanId) {
            // Dapatkan informasi bahan dari model
            $bahan = $this->bahanModel->find($bahanId);

            // Cek apakah bahan ada dan stok cukup
            if ($bahan && $bahan['stok_bahan'] >= $jumlah[$key]) {
                // Masukkan data ke tabel pencucian_bahan
                $this->pencucianBahanModel->insert([
                    'id_cuci' => $idCuci,
                    'id_bahan' => $bahanId,
                    'jumlah_bahan' => $jumlah[$key]
                ]);

                // Kurangi stok bahan
                $newStok = $bahan['stok_bahan'] - $jumlah[$key];
                $this->bahanModel->update($bahanId, ['stok_bahan' => $newStok]);
            } else {
                return redirect()->to('pengelolaan/pencucian')->with('error', 'Stok bahan tidak cukup');
            }
        }
        return redirect()->to('pengelolaan/pencucian')->with('message', 'Bahan berhasil disimpan.');
    }

    public function updateBahan($id_cuci)
    {
        // Periksa apakah data pencucian tersedia
        $pencucian = $this->pencucianModel->find($id_cuci);

        if (!$pencucian) {
            return redirect()->to('/pencucian')->with('error', 'Data tidak ditemukan.');
        }

        // Ambil bahan baru dari tabel pencucian_bahan
        $bahanBaru = $this->pencucianBahanModel->where('id_cuci', $id_cuci)->findAll();

        if ($bahanBaru) {
            // Menggabungkan bahan yang lama dan baru
            $bahanLama = $pencucian['Bahan Digunakan'];
            $bahanLamaArray = explode(',', $bahanLama);

            foreach ($bahanBaru as $item) {
                $bahanLamaArray[] = $item['id_bahan']; // Sesuaikan dengan nama kolom
            }

            // Hilangkan duplikasi dan gabungkan
            $bahanGabungan = implode(',', array_unique($bahanLamaArray));

            // Update kolom bahan digunakan di tabel pencucian
            $this->pencucianModel->update($id_cuci, [
                'Bahan Digunakan' => $bahanGabungan,
            ]);
        }

        return redirect()->to('pengelolaan/pencucian')->with('message', 'Data pencucian berhasil diperbarui.');
    }
    public function move($id_cuci)
    {
        // Ambil data pencucian berdasarkan id_cuci
        $pencucian = $this->pencucianModel->find($id_cuci);

        if (!$pencucian) {
            return redirect()->back()->with('error', 'Data pencucian tidak ditemukan');
        }

        // Cek apakah data sudah ada di pengeringan
        $existingPengeringan = $this->pengeringanModel->where('id_cuci', $id_cuci)->first();


        if ($existingPengeringan) {
            return redirect()->back()->with('error', 'Data sudah ada di pengeringan');
        }

        try {
            // Start Database Transaction
            $this->db->transStart();
            // Siapkan data untuk tabel pengeringan
            $data = [
                'id_cuci' => $id_cuci,
                'tanggal_mulai' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];

            // Insert data ke tabel pengeringan
            $this->pengeringanModel->insert($data);

            // Update status pencucian
            $this->pencucianModel->update($id_cuci, [
                'pencucian_status' => 'completed'
            ]);

            // Complete Transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {

                return redirect()->back()->with('error', 'Terjadi kesalahan saat memindahkan data');
            }

            return redirect()->to('pengelolaan/pencucian')->with('message', 'Data berhasil dipindahkan ke pengeringan');
        } catch (\Exception $e) {
            log_message('error', 'Error in move method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function getBahanOptions()
    {
        // Ambil semua data bahan
        $bahan = $this->bahanModel->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $bahan
        ]);
    }

    public function getExistingBahan($idCuci)
    {
        // Ambil bahan terkait berdasarkan ID cuci
        $pencucian = $this->pencucianBahanModel->where('id_cuci', $idCuci)->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $pencucian
        ]);
    }
}
