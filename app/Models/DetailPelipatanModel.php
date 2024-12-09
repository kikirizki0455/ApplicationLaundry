<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPelipatanModel extends Model
{
    protected $table = 'detail_pelipatan';
    protected $primaryKey = 'id_detail_pelipatan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['id_pelipatan', 'id_barang', 'jumlah_barang', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getDetailByPelipatan($id_pelipatan)
    {
        // Validasi input
        if (empty($id_pelipatan)) {
            return [
                'detail_pelipatan' => [],
                'timbangan_barang' => []
            ];
        }

        // Main query untuk mendapatkan detail pelipatan
        $detail_pelipatan = $this->db->table('detail_pelipatan')
            ->select('detail_pelipatan.*, barang.nama_barang, barang.stok')
            ->join('barang', 'barang.id_barang = detail_pelipatan.id_barang')
            ->join('pelipatan', 'pelipatan.id_pelipatan = detail_pelipatan.id_pelipatan')
            ->where('detail_pelipatan.id_pelipatan', $id_pelipatan)
            ->get()
            ->getResultArray();

        // Query untuk mendapatkan data timbangan barang
        $timbangan_barang = $this->db->table('timbangan_barang as tb')
            ->select('tb.*, 
                      br.nama_barang, 
                      tb.jumlah, 
                      p.id_pelipatan, 
                      r.id_ruangan, 
                      r.nama_ruangan')
            ->join('barang br', 'br.id_barang = tb.id_barang', 'inner')
            ->join('timbangan t', 't.id_timbangan = tb.id_timbangan', 'inner')
            ->join('pencucian pc', 'pc.id_timbangan = t.id_timbangan', 'inner')
            ->join('pengeringan pg', 'pg.id_cuci = pc.id_cuci', 'inner')
            ->join('penyetrikaan ps', 'ps.id_pengeringan = pg.id_pengeringan', 'inner')
            ->join('pelipatan p', 'p.id_penyetrikaan = ps.id_penyetrikaan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->where('p.id_pelipatan', $id_pelipatan)
            ->get()
            ->getResultArray();

        // Tambahkan pengecekan untuk menghindari kesalahan
        return [
            'detail_pelipatan' => $detail_pelipatan ?? [],
            'timbangan_barang' => $timbangan_barang ?? []
        ];
    }
}
