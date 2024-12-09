<?php

namespace App\Models;

use CodeIgniter\Model;

class RuanganBarangModel extends Model
{
    protected $table = 'ruangan_barang';
    protected $primaryKey = 'id_ruangan_barang'; // Perbaikan: sesuaikan dengan nama primary key yang benar

    protected $allowedFields = ['id_ruangan', 'id_barang', 'jumlah'];

    public function getRuanganBarang($id_ruangan)
    {
        return $this->db->table($this->table . ' rb')
            ->select('b.nama_barang, rb.jumlah, rb.id_barang')
            ->join('barang b', 'b.id_barang = rb.id_barang')
            ->where('rb.id_ruangan', $id_ruangan) // Perbaikan: id_ruangan, bukan id_baran
            ->get()
            ->getResultArray();
    }

    public function getRuanganWithBarang()
    {
        return $this->select('ruangan.*, barang.nama_barang, ruangan_barang.jumlah as stok')
            ->join('ruangan_barang', 'ruangan_barang.id_ruangan = ruangan.id_ruangan', 'left')
            ->join('barang', 'barang.id_barang = ruangan_barang.id_barang', 'left')
            ->findAll();
    }
    public function sum($id_ruangan, $id_barang)
    {
        return $this->selectSum('jumlah')
            ->where('id_ruangan', $id_ruangan)
            ->where('id_barang', $id_barang)
            ->first(); // Mengambil satu hasil
    }
    public function sumBarang($barang_id)
    {
        return $this->selectSum('jumlah')
            ->where('id_barang', $barang_id)
            ->first(); // Mengambil satu hasil
    }
}
