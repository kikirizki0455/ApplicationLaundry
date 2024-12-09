<?php

namespace App\Models;

use CodeIgniter\Model;

class PencucianBahanModel extends Model
{
    protected $table = 'pencucian_bahan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_mesin', 'id_bahan', 'jumlah_bahan'];

    public function getBahanByMesin($id_mesin)
    {
        return $this->db->table($this->table . ' pb')
            ->select('b.nama_bahan, pb.jumlah_bahan')
            ->join('bahan b', 'b.id_bahan = pb.id_bahan')
            ->where('pb.id_mesin', $id_mesin)
            ->get()
            ->getResultArray();
    }
}
