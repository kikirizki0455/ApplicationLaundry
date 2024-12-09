<?php

namespace App\Models;

use CodeIgniter\Model;

class MesinBahanModel extends Model
{
    protected $table = 'mesin_bahan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_mesin', 'id_bahan', 'jumlah_bahan'];
    protected $useTimestamps = false;

    // Tambahkan validasi
    protected $validationRules = [
        'id_mesin' => 'required|numeric',
        'id_bahan' => 'required|numeric',
        'jumlah_bahan' => 'required|numeric|greater_than[0]'
    ];

    public function getBahanByMesin($id_mesin)
    {
        return $this->db->table($this->table . ' mb')
            ->select('b.nama_bahan, mb.jumlah_bahan, mb.id_bahan')
            ->join('bahan b', 'b.id_bahan = mb.id_bahan')
            ->where('mb.id_mesin', $id_mesin)
            ->get()
            ->getResultArray();
    }
}
