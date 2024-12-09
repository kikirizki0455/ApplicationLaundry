<?php

namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{

    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $allowedFields = ['nama_ruangan', 'status'];


    public function getBarang()
    {
        $ruangan = $this->db->table($this->table . ' as r')
            ->select('
            r.id_ruangan,
            r.nama_ruangan
        ') // Hapus koma terakhir disini
            ->get()
            ->getResultArray();

        // Ubah query untuk mengambil barang dari tabel yang benar
        foreach ($ruangan as &$r) {
            $barang = $this->db->table('ruangan_barang as rb') // Mengambil data dari tabel ruangan_barang
                ->select('b.nama_barang, rb.jumlah, rb.id_barang') // Pastikan untuk mengambil nama_barang dari tabel barang (b)
                ->join('barang b', 'b.id_barang = rb.id_barang') // Join dengan tabel barang untuk mendapatkan nama_barang
                ->where('rb.id_ruangan', $r['id_ruangan']) // Hapus spasi ekstra pada id_ruangan
                ->get()
                ->getResultArray();

            $r['barang'] = $barang;
        }

        return $ruangan;
    }



    public function saveRuanganWithBarang($ruanganData, $barangData)
    {
        // Mulai transaksi database
        $this->db->transStart();

        try {
            // Insert ruangan dan dapatkan ID
            $this->insert($ruanganData);
            $idRuangan = $this->insertID();

            // Siapkan model untuk ruangan_barang
            $ruanganBarangModel = new RuanganBarangModel();

            // Insert barang ke ruangan_barang
            foreach ($barangData as $barang) {
                $ruanganBarangModel->insert([
                    'id_ruangan' => $idRuangan,
                    'id_barang' => $barang['id_barang'],
                    'jumlah' => $barang['jumlah']
                ]);

                // Optional: Update stok barang jika diperlukan
                $barangModel = new BarangModel();
                $barangModel->update($barang['id_barang'], [
                    'stok' => $barangModel->find($barang['id_barang'])['stok'] - $barang['jumlah']
                ]);
            }

            // Commit transaksi
            $this->db->transCommit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            $this->db->transRollback();
            log_message('error', $e->getMessage());
            return false;
        }
    }
}
