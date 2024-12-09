<?php

namespace App\Models;

use CodeIgniter\Model;

class TimbanganBersihModel extends Model
{
    protected $table = 'timbangan_bersih';
    protected $primaryKey = 'id_timbangan_bersih';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_pelipatan',
        'no_invoice',
        'berat_bersih',
        'berat_kotor',
        'berat_bersih',
        'status',
        'tanggal_pengiriman',
        'ruangan'

    ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getDetailByTimbangan()
    {
        return $this->select('timbangan_bersih.*, barang.nama_barang, barang.stok, timbangan.no_invoice') // Mengambil no_invoice dari timbangan
            ->join('detail_timbangan_bersih', 'detail_timbangan_bersih.id_timbangan_bersih = timbangan_bersih.id_timbangan_bersih')
            ->join('barang', 'barang.id_barang = detail_timbangan_bersih.id_barang')
            ->join('pelipatan', 'pelipatan.id_pelipatan = timbangan_bersih.id_pelipatan')  // Melanjutkan ke pelipatan
            ->join('penyetrikaan', 'penyetrikaan.id_penyetrikaan = pelipatan.id_penyetrikaan')
            ->join('pengeringan', 'pengeringan.id_pengeringan = penyetrikaan.id_pengeringan')
            ->join('pencucian', 'pencucian.id_cuci = pengeringan.id_cuci')
            ->join('timbangan', 'timbangan.id_timbangan = pencucian.id_timbangan') // Join ke timbangan untuk mendapatkan no_invoice
            ->findAll();
    }

    public function getTimbanganBersihWithDetails()
    {
        // Ambil data timbangan bersih dengan semua relasi yang diperlukan
        $timbanganBersih = $this->db->table('timbangan_bersih as tb')
            ->select('
            tb.id_timbangan_bersih,
            pl.id_pelipatan,
            t.no_invoice, 
            t.id_ruangan, 
            t.id_timbangan, 
            t.berat_barang AS berat_kotor, 
            tanggal_pengiriman,
            r.nama_ruangan,
            tb.status,
            tb.berat_bersih
        ')
            ->join('pelipatan pl', 'pl.id_pelipatan = tb.id_pelipatan', 'inner')
            ->join('penyetrikaan py', 'py.id_penyetrikaan = pl.id_penyetrikaan', 'inner')
            ->join('pengeringan p', 'p.id_pengeringan = py.id_pengeringan', 'inner')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci', 'inner')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin', 'left')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai', 'left')
            ->groupBy('tb.id_timbangan_bersih')
            ->get()
            ->getResultArray();

        // Iterasi hasil timbangan bersih
        foreach ($timbanganBersih as &$item) {

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

        return $timbanganBersih;
    }


    public function getTimbanganBersihById($id)
    {
        // Ambil data timbangan bersih dengan semua relasi yang diperlukan berdasarkan id_timbangan_bersih
        $timbanganBersih = $this->db->table('timbangan_bersih as tb')
            ->select('
            tb.id_timbangan_bersih,
            pl.id_pelipatan,
            t.no_invoice, 
            t.id_ruangan, 
            t.id_timbangan, 
            t.berat_barang AS berat_kotor, 
            tanggal_pengiriman,
            r.nama_ruangan,
            tb.status
        ')
            ->join('pelipatan pl', 'pl.id_pelipatan = tb.id_pelipatan', 'inner')
            ->join('penyetrikaan py', 'py.id_penyetrikaan = pl.id_penyetrikaan', 'inner')
            ->join('pengeringan p', 'p.id_pengeringan = py.id_pengeringan', 'inner')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci', 'inner')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan', 'inner')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan', 'left')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin', 'left')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai', 'left')
            ->where('tb.id_timbangan_bersih', $id) // Menambahkan kondisi untuk ID tertentu
            ->get()
            ->getRowArray(); // Ambil hanya satu baris karena berdasarkan ID

        // Jika tidak ditemukan, return null
        if (!$timbanganBersih) {
            return null;
        }

        // Ambil data barang berdasarkan id_timbangan
        $barangQuery = $this->db->table('timbangan_barang as tb')
            ->select('br.nama_barang, tb.jumlah')
            ->join('barang br', 'br.id_barang = tb.id_barang', 'inner')
            ->where('tb.id_timbangan', $timbanganBersih['id_timbangan'])
            ->get();
        $timbanganBersih['barang'] = $barangQuery->getResultArray();

        // Ambil detail pelipatan berdasarkan id_pelipatan
        $detailPelipatanQuery = $this->db->table('detail_pelipatan as dp')
            ->select('dp.id_detail_pelipatan, dp.id_barang, dp.jumlah_barang, b.nama_barang')
            ->join('barang b', 'b.id_barang = dp.id_barang', 'inner')
            ->where('dp.id_pelipatan', $timbanganBersih['id_pelipatan'])
            ->get();
        $timbanganBersih['detail_pelipatan'] = $detailPelipatanQuery->getResultArray();

        return $timbanganBersih;
    }

    public function getDetailBarangKotor($id_timbangan_bersih)
    {
        return $this->select('tp.* ')
            ->from('timbangan_bersih ts')
            ->join('pelipatan pl', 'pl.id_pelipatan = ts.id_pelipatan')
            ->join('penyetrikaan ps', 'ps.id_penyetrikaan = pl.id_penyetrikaan')
            ->join('pengeringan pg', 'pg.id_pengeringan = ps.id_pengeringan')
            ->join('pencucian pc', 'pc.id_cuci = pg.id_cuci')
            ->join('timbangan t', 't.id_timbangan = pc.id_timbangan')
            ->join('timbangan_barang tp', 'tp.id_timbangan = t.id_timbangan')
            ->join('barang', 'barang.id_barang = tp.id_barang')
            ->where('ts.id_timbangan_bersih', $id_timbangan_bersih)
            ->groupBy('tp.id')
            ->findAll();
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
}
