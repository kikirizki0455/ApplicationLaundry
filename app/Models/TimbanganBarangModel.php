<?php

namespace App\Models;

use CodeIgniter\Model;

class TimbanganBarangModel extends Model
{
    protected $table = 'timbangan_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_timbangan',
        'nama_barang',
        'jumlah'
    ];
    public function getBarangByTimbangan($id_timbangan)
    {
        return $this->select('timbangan_barang.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = timbangan_barang.id_barang')
            ->where('id_timbangan', $id_timbangan)
            ->findAll();
    }
}
