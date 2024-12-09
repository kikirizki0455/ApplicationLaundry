<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTimbanganBersihModel extends Model
{
    protected $table = 'detail_timbangan_bersih';
    protected $primaryKey = 'id_detail_timbangan_bersih';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['id_timbangan_bersih', 'id_barang', 'jumlah_barang', 'berat_bersih'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    public function getDetailByTimbangan($id_timbangan_bersih)
    {
        return $this->select('detail_timbangan_bersih.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = detail_timbangan_bersih.id_barang')
            ->where('detail_timbangan_bersih.id_timbangan_bersih', $id_timbangan_bersih)
            ->findAll();
    }
}
