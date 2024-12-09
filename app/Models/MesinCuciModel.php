<?php

namespace App\Models;

use CodeIgniter\Model;

class MesinCuciModel extends Model
{
    protected $table = 'mesin_cuci';
    protected $primaryKey = 'id_mesin';
    protected $allowedFields = ['nama_mesin', 'kapasitas', 'status', 'kategori'];

    public function getMesin()
    {
        $mesin = $this->db->table($this->table . ' as m')
            ->select('
            m.id_mesin,
            m.nama_mesin,
            m.kapasitas,
            m.status,
            m.kategori
        ') // Hapus koma terakhir disini
            ->get()
            ->getResultArray();

        // Ubah query untuk mengambil bahan dari tabel yang benar
        foreach ($mesin as &$m) {
            $bahan = $this->db->table('mesin_bahan as mb') // Ubah dari pencucian_bahan ke mesin_bahan
                ->select('b.nama_bahan, mb.jumlah_bahan')
                ->join('bahan b', 'b.id_bahan = mb.id_bahan')
                ->where('mb.id_mesin', $m['id_mesin'])
                ->get()
                ->getResultArray();

            $m['bahan'] = $bahan;
        }

        return $mesin;
    }
    public function getMesinByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }
    public function insertMesin($data)
    {
        // Insert mesin tanpa bahan
        $this->insert($data);
    }
}
