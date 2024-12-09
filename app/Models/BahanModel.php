<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanModel extends Model
{
    protected $table      = 'bahan';
    protected $primaryKey = 'id_bahan';

    protected $allowedFields = ['nama_bahan', 'stok_bahan'];

    // Menambahkan metode untuk mendapatkan semua bahan
    public function getAllBahan()
    {
        return $this->findAll();
    }

    // Menambahkan metode untuk mendapatkan bahan berdasarkan ID
    public function getBahanById($id)
    {
        return $this->find($id);
    }

    // Metode untuk menambah bahan baru
    public function addBahan($data)
    {
        return $this->insert($data);
    }

    // Metode untuk memperbarui bahan
    public function updateBahan($id, $data)
    {
        return $this->update($id, $data);
    }

    // Metode untuk menghapus bahan
    public function deleteBahan($id)
    {
        return $this->delete($id);
    }
}
