<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyetrikaanModel extends Model
{
    protected $table = 'penyetrikaan';
    protected $primaryKey = 'id_penyetrikaan';
    protected $allowedFields = [
        'id_pengeringan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'created_at',
        'updated_at'
    ];

    public function getPenyetrikaanDetails()
    {

        // Ambil data pengeringan dengan semua status kecuali completed
        $penyetrikaan = $this->db->table('penyetrikaan as py')
            ->select('py.id_penyetrikaan, p.id_pengeringan, p.id_cuci, t.no_invoice, t.id_timbangan, t.berat_barang, 
                      t.status AS status_timbangan, t.id_ruangan, py.status ,
                      p.tanggal_mulai, m.id_mesin, pg.nama_pegawai, m.nama_mesin, 
                      p.tanggal_selesai, t.id_barang, c.id_bahan, c.jumlah_bahan, r.nama_ruangan')
            ->join('pengeringan p', 'p.id_pengeringan = py.id_pengeringan')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin', 'left')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai', 'left')

            ->groupBy('py.id_penyetrikaan')
            ->get()
            ->getResultArray();

        // Iterasi hasil pengeringan
        foreach ($penyetrikaan as &$item) {
            // Ambil data bahan berdasarkan id_mesin
            $bahanQuery = $this->db->table('mesin_bahan as mb')
                ->select('b.nama_bahan, mb.jumlah_bahan')
                ->join('bahan b', 'b.id_bahan = mb.id_bahan', 'inner')
                ->where('mb.id_mesin', $item['id_mesin'])
                ->get();
            $item['bahan'] = $bahanQuery->getResultArray();

            // Ambil data barang berdasarkan id_timbangan
            $barangQuery = $this->db->table('timbangan_barang as tb')
                ->select('br.nama_barang, tb.jumlah')
                ->join('barang br', 'br.id_barang = tb.id_barang', 'inner')
                ->where('tb.id_timbangan', $item['id_timbangan'])
                ->get();
            $item['barang'] = $barangQuery->getResultArray();
        }

        return $penyetrikaan;
    }
}
