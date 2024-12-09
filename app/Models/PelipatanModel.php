<?php

namespace App\Models;

use CodeIgniter\Model;

class PelipatanModel extends Model
{
    protected $table = 'pelipatan';
    protected $primaryKey = 'id_pelipatan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['id_penyetrikaan', 'no_invoice', 'status', 'tanggal_mulai', 'tanggal_selesai', 'berat_barang'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getPelipatanWithDetails()
    {
        return $this->select('pelipatan.*, penyetrikaan.id_pengeringan, timbangan.nama_barang, timbangan.berat_barang, pegawai.nama_pegawai,timbangan.no_invoice')
            ->join('penyetrikaan', 'penyetrikaan.id_penyetrikaan = pelipatan.id_penyetrikaan')
            ->join('pengeringan', 'pengeringan.id_pengeringan = penyetrikaan.id_pengeringan')
            ->join('pencucian', 'pencucian.id_cuci = pengeringan.id_cuci')
            ->join('timbangan', 'timbangan.id_timbangan = pencucian.id_timbangan')
            ->join('pegawai', 'pegawai.id_pegawai = timbangan.id_pegawai')
            ->findAll();
    }
    public function getBarangByPelipatan($id_pelipatan)
    {
        return $this->db->table('pelipatan')
            ->select('barang.id_barang, timbangan_barang.jumlah')
            ->join('timbangan', 'timbangan.id_timbangan = pelipatan.id_timbangan')
            ->join('timbangan_barang', 'timbangan_barang.id_timbangan = timbangan.id_timbangan')
            ->join('barang', 'barang.id_barang = timbangan_barang.id_barang')
            ->where('pelipatan.id_pelipatan', $id_pelipatan)
            ->get()
            ->getResultArray();
    }

    public function getPelipatanDetails()
    {
        // Ambil data pengeringan dengan semua status kecuali completed
        $pelipatan = $this->db->table('pelipatan as pl')
            ->select('pl.id_pelipatan, py.id_penyetrikaan, p.id_pengeringan, p.id_cuci, 
                  t.no_invoice, t.id_timbangan, t.berat_barang, 
                  t.status AS status_timbangan, t.id_ruangan, pl.status,
                  p.tanggal_mulai, m.id_mesin, pg.nama_pegawai, m.nama_mesin, 
                  p.tanggal_selesai, t.id_barang, c.id_bahan, c.jumlah_bahan, r.nama_ruangan')
            ->join('penyetrikaan py', 'py.id_penyetrikaan = pl.id_penyetrikaan')
            ->join('pengeringan p', 'p.id_pengeringan = py.id_pengeringan')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin', 'left')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai', 'left')
            ->groupBy('pl.id_pelipatan')
            ->get()
            ->getResultArray();

        // Iterasi hasil pengeringan
        foreach ($pelipatan as &$item) {
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

            // Ambil detail pelipatan berdasarkan id_pelipatan
            $detailPelipatanQuery = $this->db->table('detail_pelipatan as dp')
                ->select('dp.id_detail_pelipatan, dp.id_barang, dp.jumlah_barang, b.nama_barang')
                ->join('barang b', 'b.id_barang = dp.id_barang', 'inner')
                ->where('dp.id_pelipatan', $item['id_pelipatan'])
                ->get();
            $item['detail_pelipatan'] = $detailPelipatanQuery->getResultArray();
        }

        return $pelipatan;
    }

    public function getPelipatanDetailsById($id)
    {
        // Ambil data pelipatan berdasarkan ID
        $pelipatan = $this->db->table('pelipatan as pl')
            ->select('pl.id_pelipatan, py.id_penyetrikaan, p.id_pengeringan, p.id_cuci, 
                  t.no_invoice, t.id_timbangan, t.berat_barang, 
                  t.status AS status_timbangan, t.id_ruangan, pl.status,
                  p.tanggal_mulai, m.id_mesin, pg.nama_pegawai, m.nama_mesin, 
                  p.tanggal_selesai, t.id_barang, c.id_bahan, c.jumlah_bahan, r.nama_ruangan')
            ->join('penyetrikaan py', 'py.id_penyetrikaan = pl.id_penyetrikaan')
            ->join('pengeringan p', 'p.id_pengeringan = py.id_pengeringan')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin', 'left')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai', 'left')
            ->where('pl.id_pelipatan', $id)
            ->groupBy('pl.id_pelipatan')
            ->get()
            ->getRowArray(); // Mengambil satu baris data

        if (!$pelipatan) {
            return null; // Jika data tidak ditemukan, kembalikan null
        }

        // Ambil data bahan berdasarkan id_mesin
        $bahanQuery = $this->db->table('mesin_bahan as mb')
            ->select('b.nama_bahan, mb.jumlah_bahan')
            ->join('bahan b', 'b.id_bahan = mb.id_bahan', 'inner')
            ->where('mb.id_mesin', $pelipatan['id_mesin'])
            ->get();
        $pelipatan['bahan'] = $bahanQuery->getResultArray();

        // Ambil data barang berdasarkan id_timbangan
        $barangQuery = $this->db->table('timbangan_barang as tb')
            ->select('br.nama_barang, tb.jumlah')
            ->join('barang br', 'br.id_barang = tb.id_barang', 'inner')
            ->where('tb.id_timbangan', $pelipatan['id_timbangan'])
            ->get();
        $pelipatan['barang'] = $barangQuery->getResultArray();

        // Ambil detail pelipatan berdasarkan id_pelipatan
        $detailPelipatanQuery = $this->db->table('detail_pelipatan as dp')
            ->select('dp.id_detail_pelipatan, dp.id_barang, dp.jumlah_barang, b.nama_barang')
            ->join('barang b', 'b.id_barang = dp.id_barang', 'inner')
            ->where('dp.id_pelipatan', $pelipatan['id_pelipatan'])
            ->get();
        $pelipatan['detail_pelipatan'] = $detailPelipatanQuery->getResultArray();

        return $pelipatan;
    }


    public function getDetailBarang($id_timbangan_bersih)
    {
        return $this->select('detail_pelipatan.*, barang.nama_barang')
            ->join('pelipatan', 'pelipatan.id_pelipatan = timbangan_bersih.id_pelipatan')
            ->join('detail_pelipatan', 'detail_pelipatan.id_pelipatan = pelipatan.id_pelipatan')
            ->join('barang', 'barang.id_barang = detail_pelipatan.id_barang')
            ->where('timbangan_bersih.id_timbangan_bersih', $id_timbangan_bersih)
            ->findAll();
    }

    public function movePelipatanwithDetails($id_pelipatan)
    {
        return $this->select('pelipatan.*, penyetrikaan.id_pengeringan, timbangan.nama_barang, timbangan.berat_barang, pegawai.nama_pegawai,timbangan.no_invoice, detail_pelipatan.jumlah_barang')
            ->join('penyetrikaan', 'penyetrikaan.id_penyetrikaan = pelipatan.id_penyetrikaan')
            ->join('pengeringan', 'pengeringan.id_pengeringan = penyetrikaan.id_pengeringan')
            ->join('pencucian', 'pencucian.id_cuci = pengeringan.id_cuci')
            ->join('timbangan', 'timbangan.id_timbangan = pencucian.id_timbangan')
            ->join('pegawai', 'pegawai.id_pegawai = timbangan.id_pegawai')
            ->join('detail_pelipatan', 'detail_pelipatan.id_pelipatan = pelipatan.id_pelipatan')
            ->where('pelipatan.id_pelipatan', $id_pelipatan)
            ->findAll();
    }
}
