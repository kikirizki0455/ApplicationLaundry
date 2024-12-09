<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang'; // Nama tabel
    protected $primaryKey = 'id_barang'; // Primary key
    protected $allowedFields = ['nama_barang', 'stok']; // Kolom yang bisa di-insert/update


    public function getBarangWithStokDicuci()
    {
        return $this->db->table('barang b')
            ->select('b.id_barang, b.nama_barang, b.stok AS total_stok, stok_dicuci')
            ->join('ruangan_barang rb', 'rb.id_barang = b.id_barang', 'left')                  // Hubungkan ruangan_barang dengan barang
            ->join('ruangan r', 'rb.id_ruangan = r.id_ruangan', 'left')                       // Hubungkan ruangan_barang dengan ruangan
            ->join('timbangan t', 'r.id_ruangan = t.id_ruangan', 'left')                      // Hubungkan ruangan dengan timbangan
            ->join('timbangan_barang tb', 't.id_timbangan = tb.id_timbangan', 'left')         // Hubungkan timbangan dengan timbangan_barang
            ->groupBy('b.id_barang, b.nama_barang, b.stok')                                   // Group berdasarkan barang
            ->get()
            ->getResult();
    }
    public function kurangiStokDicuci($id_barang, $jumlah, $id_ruangan)
    {
        // Periksa apakah barang dengan id tersebut ada dan memiliki stok dicuci
        $barang = $this->db->table('barang')
            ->select('stok_dicuci')
            ->where('id_barang', $id_barang)
            ->get()
            ->getRow();

        if (!$barang) {
            // Log error atau handle dengan cara yang sesuai
            log_message('error', "Barang dengan ID $id_barang tidak ditemukan");
            return false;
        }

        // Pastikan jumlah stok dicuci cukup
        if ($barang->stok_dicuci < $jumlah) {
            log_message('error', "Stok dicuci tidak mencukupi untuk barang ID $id_barang");
            return false;
        }

        // Mengurangi stok barang yang dicuci hanya untuk ruangan tertentu
        // Pastikan bahwa barang tersebut berada di ruangan yang sesuai
        $ruanganBarang = $this->db->table('ruangan_barang')
            ->select('jumlah')
            ->where('id_ruangan', $id_ruangan)
            ->where('id_barang', $id_barang)
            ->get()
            ->getRow();

        if ($ruanganBarang) {
            // Kurangi stok barang yang dicuci berdasarkan jumlah yang ada di ruangan
            $newJumlah = $ruanganBarang->jumlah - $jumlah;

            if ($newJumlah >= 0) {
                // Update jumlah stok barang di ruangan
                $this->db->table('ruangan_barang')
                    ->where('id_ruangan', $id_ruangan)
                    ->where('id_barang', $id_barang)
                    ->update(['jumlah' => $newJumlah]);

                // Update stok dicuci di tabel barang
                $updateData = [
                    'stok_dicuci' => "stok_dicuci - $jumlah"  // Kurangi stok_dicuci
                ];

                $affectedRows = $this->db->table('barang')
                    ->where('id_barang', $id_barang)
                    ->set($updateData, false)  // Set parameter kedua ke false untuk menghindari pemrosesan otomatis
                    ->update();

                if ($affectedRows) {
                    return true;
                } else {
                    log_message('error', "Tidak ada data yang terupdate untuk barang ID $id_barang");
                    return false;
                }
            } else {
                log_message('error', "Jumlah barang di ruangan ($ruanganBarang->jumlah) tidak mencukupi untuk pengurangan stok.");
                return false;
            }
        } else {
            log_message('error', "Barang dengan ID $id_barang tidak ditemukan di ruangan dengan ID $id_ruangan");
            return false;
        }
    }
}
