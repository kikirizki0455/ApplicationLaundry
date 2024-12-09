<?php

namespace App\Controllers\Distribusi;

use App\Controllers\BaseController;
use App\Models\TimbanganBersihModel;
use App\Models\PengirimanModel;


class Pengiriman extends BaseController
{

    protected $timbanganBersihModel, $pengirimanModel;
    public function __construct()
    {
        $this->timbanganBersihModel = new TimbanganBersihModel();
        $this->pengirimanModel = new PengirimanModel();
        helper(['file', 'filesystem']);
    }

    public function konfirmasi($id)
    {

        $timbangan = $this->timbanganBersihModel->getTimbanganBersihById($id);
        // Logika untuk menampilkan konfirmasi pengiriman

        $data = [
            'title' => 'Konfirmasi || LA-DUS',
            'timbangan' => $timbangan
        ];
        return view('distribusi/pengiriman', $data);
    }

    public function simpan_konfirmasi($id_timbangan_bersih)
    {
        // Pastikan ini adalah request POST
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Metode permintaan tidak valid');
        }

        // Validasi CSRF token bawaan CodeIgniter
        if (!$this->validate(['csrf_test_name' => 'required'])) {
            return redirect()->back()->with('error', 'Validasi token CSRF gagal');
        }

        // Validasi request
        $signatureData = $this->request->getPost('signature_data');

        if (empty($signatureData)) {
            return redirect()->back()->with('error', 'Tanda tangan wajib diisi!');
        }

        // Decode base64 tanda tangan
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $decodedImage = base64_decode($signatureData);

        // Simpan tanda tangan sebagai file
        $signatureFileName = 'signature_' . $id_timbangan_bersih . '_' . time() . '.png';
        $filePath = WRITEPATH . 'uploads/' . $signatureFileName;

        // Debug log path file
        log_message('debug', 'File path: ' . $filePath);

        // Menyimpan file tanda tangan menggunakan metode CodeIgniter
        try {
            // Pastikan direktori ada
            if (!is_dir(WRITEPATH . 'uploads/')) {
                mkdir(WRITEPATH . 'uploads/', 0755, true);
            }

            // Gunakan file_put_contents dengan error handling
            if (file_put_contents($filePath, $decodedImage) === false) {
                log_message('error', 'Gagal menyimpan file pada path: ' . $filePath);
                return redirect()->back()->with('error', 'Gagal menyimpan tanda tangan!');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error menyimpan file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan tanda tangan: ' . $e->getMessage());
        }

        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Simpan data ke tabel pengiriman
            $pengirimanData = [
                'id_timbangan_bersih' => $id_timbangan_bersih,
                'signature_path' => $signatureFileName,
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $pengirimanModel = new PengirimanModel();
            $saved = $pengirimanModel->insert($pengirimanData);

            // Update status timbangan bersih menjadi delivered
            $timbanganBersihModel = new TimbanganBersihModel();
            $updated = $timbanganBersihModel->update($id_timbangan_bersih, [
                'status' => 'delivered',
                'tanggal_pengiriman' => date('Y-m-d H:i:s')
            ]);

            // Commit transaksi jika semua operasi berhasil
            if ($saved && $updated) {
                $db->transCommit();
                return redirect()->to('distribusi/timbangan_bersih')
                    ->with('success', 'Timbangan berhasil dikirim ke ruangan!');
            } else {
                // Rollback jika ada kesalahan
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal menyimpan data pengiriman!');
            }
        } catch (\Exception $e) {
            // Rollback jika terjadi exception
            $db->transRollback();
            log_message('error', 'Error in simpan_konfirmasi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateCsrfToken()
    {
        // Gunakan metode bawaan CodeIgniter untuk validasi CSRF
        $security = \Config\Services::security();

        try {
            // Metode validate akan melempar exception jika token tidak valid
            $security->verify($this->request);
            return true;
        } catch (\Exception $e) {
            // Log error jika perlu
            log_message('error', 'CSRF Token Validation Failed: ' . $e->getMessage());
            return false;
        }
    }
    public function refreshCsrf()
    {
        $response = [
            'csrf_token' => csrf_hash()
        ];
        return $this->response->setJSON($response);
    }
}
