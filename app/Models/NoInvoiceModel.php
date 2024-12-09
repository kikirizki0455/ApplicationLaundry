<?php

namespace App\Models;

use CodeIgniter\Model;

class NoInvoiceModel extends Model
{
    protected $table = 'timbangan';
    protected $primaryKey = 'id_timbangan';
    protected $pegawaiModel;

    public function __construct()
    {
        parent::__construct(); // Memanggil konstruktor parent
        $this->pegawaiModel = new PegawaiModel(); // Inisialisasi model pegawai
    }

    public function generateInvoiceNo()
    {
        // Ambil data terakhir untuk generate nomor invoice
        $lastInvoice = $this->db->table($this->table)
            ->select('id_timbangan, id_pegawai, id_ruangan,id_mesin')
            ->orderBy('id_timbangan', 'DESC')
            ->get()
            ->getFirstRow('array');

        // Ambil tanggal hari ini
        $today = new \DateTime();
        $dd = $today->format('d');
        $mm = $today->format('m');
        $yyyy = $today->format('Y');

        // Tentukan ID Pegawai untuk invoice
        $id_pegawai = $lastInvoice ? $lastInvoice['id_pegawai'] : null;

        $no_pegawai = '001'; // default

        if ($id_pegawai) {
            $pegawaiData = $this->pegawaiModel->getPegawaiById($id_pegawai);
            if ($pegawaiData && isset($pegawaiData['nomor_pegawai'])) { // Tambahkan pengecekan di sini
                $no_pegawai = str_pad($pegawaiData['nomor_pegawai'], 3, '0', STR_PAD_LEFT);
            }
        }

        // Tentukan ID Ruangan dan Mesin Cuci
        $lastRoomId = $lastInvoice ? substr($lastInvoice['id_ruangan'], -1) : '1'; // Ambil angka terakhir dari ID Ruangan
        $lastMachineId = $lastInvoice ? substr($lastInvoice['id_mesin'], -1) : '1'; // Ambil angka terakhir dari ID Mesin Cuci

        // Tentukan urutan nomor invoice
        $lastInvoiceNo = $lastInvoice ? substr($lastInvoice['id_timbangan'], -3) : '001'; // Ambil 3 digit terakhir dari nomor timbangan
        $newInvoiceNo = str_pad((int)$lastInvoiceNo + 1, 3, '0', STR_PAD_LEFT); // Tambahkan 1 dan pastikan 3 digit

        // Generate nomor invoice
        $noInvoice = sprintf(
            '%s%s%s%s%s%s%s',
            $this->generateRandomString(3),  // 3 huruf acak
            $dd,
            $mm,
            $yyyy,
            $lastRoomId,
            $lastMachineId,
            $newInvoiceNo
        );

        return $noInvoice;
    }

    // Fungsi untuk menghasilkan 3 huruf acak
    private function generateRandomString($length = 3)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
