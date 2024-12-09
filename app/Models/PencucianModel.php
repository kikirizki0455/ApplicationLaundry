<?php

namespace App\Models;

use CodeIgniter\Model;

class PencucianModel extends Model
{
    protected $table = 'pencucian'; // Nama tabel yang digunakan
    protected $primaryKey = 'id_cuci'; // Kunci utama tabel
    protected $allowedFields = [
        'id_timbangan',
        'berat_barang',
        'no_invoice',
        'nama_barang',
        'status',
        'id_barang',
        'id_pegawai',
        'id_mesin',
        'waktu_pencucian',
        'id_bahan',
        'tanggal_mulai',
        'tanggal_selesai',
        'pencucian_status',
        'updated_at',
        'jumlah',
        'jumlah_bahan'
    ];


    public function MoveToPengeringan($idCuci)
    {
        return $this->where('id_cuci', $idCuci)->first();
    }
    public function getBahanById()
    {
        return $this->db->table('bahan');
    }
    public function getInProgressPencucian()
    {
        $pencucian = $this->db->table($this->table . ' as p')
            ->select('p.id_cuci, t.no_invoice, t.id_timbangan, t.berat_barang, 
                      t.status AS status_timbangan, t.id_ruangan, p.pencucian_status, 
                      p.tanggal_mulai, m.id_mesin, pg.nama_pegawai, m.nama_mesin, 
                      p.tanggal_selesai, p.waktu_estimasi, t.id_barang, p.id_bahan, p.jumlah_bahan,r.nama_ruangan')
            ->join('timbangan t', 'p.id_timbangan = t.id_timbangan')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan')
            ->join('mesin_cuci m', 't.id_mesin = m.id_mesin')
            ->join('pegawai pg', 't.id_pegawai = pg.id_pegawai')
            ->where('p.status', 'in_progress')
            ->whereIn('p.pencucian_status', ['pending', 'in_progress', 'ready_move', 'completed'])
            ->groupBy('p.id_cuci')
            ->get()
            ->getResultArray();

        foreach ($pencucian as &$item) {
            // Ambil data bahan berdasarkan id_mesin
            $bahanQuery = $this->db->table('mesin_bahan as mb')
                ->select('b.nama_bahan, mb.jumlah_bahan')
                ->join('bahan b', 'b.id_bahan = mb.id_bahan')
                ->where('mb.id_mesin', $item['id_mesin'])
                ->get();
            $item['bahan'] = $bahanQuery->getResultArray();

            // Ambil data barang berdasarkan id_timbangan
            $barangQuery = $this->db->table('timbangan_barang as tb')
                ->select('br.nama_barang, tb.jumlah')
                ->join('barang br', 'br.id_barang = tb.id_barang')
                ->where('tb.id_timbangan', $item['id_timbangan'])
                ->get();
            $item['barang'] = $barangQuery->getResultArray();
        }

        return $pencucian;
    }



    public function getTimbanganById($idTimbangan)
    {
        return $this->db->table('timbangan')
            ->select('id_timbangan, berat_barang')
            ->where('id_timbangan', $idTimbangan)
            ->get()
            ->getRow();
    }

    /**
     * Update status timbangan menjadi 'in_progress'.
     *
     * @param int $idTimbangan
     * @return bool
     */
    public function updateTimbanganStatus($idTimbangan)
    {
        return $this->db->table('timbangan')
            ->where('id_timbangan', $idTimbangan)
            ->update(['status' => 'in_progress']);
    }
    public function insertBatch(?array $set = null, ?bool $escape = null, int $batchSize = 100, bool $testing = false)
    {
        try {
            // Gunakan parent insertBatch dengan parameter yang diterima
            $result = parent::insertBatch($set, $escape, $batchSize, $testing);

            // Log error jika insert gagal
            if ($result === false) {
                log_message('error', 'Failed to insert batch: ' . json_encode($this->db->error()));
            }

            return $result;
        } catch (\Exception $e) {
            // Tangkap exception dan log pesan kesalahan
            log_message('error', 'Insert Batch Exception: ' . $e->getMessage());
            return false;
        }
    }
}
