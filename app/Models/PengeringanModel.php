<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeringanModel extends Model
{
    protected $table = 'pengeringan';
    protected $primaryKey = 'id_pengeringan';
    protected $allowedFields = [
        'id_cuci',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getInProgress()
    {
        return $this->where('status', 'in_progress')->findAll();
    }

    // Method untuk mendapatkan data yang sudah selesai
    public function getCompleted()
    {
        return $this->where('status', 'completed')->findAll();
    }


    // akse data yang di butuhkan pada pengeringan

    public function getPengeringanDetails()
    {
        // Ambil data pengeringan dengan semua status kecuali completed
        $pengeringan = $this->db->table('pengeringan as p')
            ->select('p.id_pengeringan, p.id_cuci, t.no_invoice, t.id_timbangan, t.berat_barang, 
                  t.status AS status_timbangan, t.id_ruangan, p.status ,
                  p.tanggal_mulai, m.id_mesin, pg.nama_pegawai, m.nama_mesin, 
                  p.tanggal_selesai, t.id_barang, c.id_bahan, c.jumlah_bahan, r.nama_ruangan')
            ->join('pencucian c', 'c.id_cuci = p.id_cuci')
            ->join('timbangan t', 't.id_timbangan = c.id_timbangan')
            ->join('ruangan r', 'r.id_ruangan = t.id_ruangan')
            ->join('mesin_cuci m', 'm.id_mesin = t.id_mesin')
            ->join('pegawai pg', 'pg.id_pegawai = t.id_pegawai')
            ->groupBy('p.id_pengeringan')
            ->get()
            ->getResultArray();

        foreach ($pengeringan as &$item) {
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

        return $pengeringan;
    }



    // Method untuk mengecek apakah data sudah ada
    public function checkExisting($id_cuci)
    {
        return $this->where('id_cuci', $id_cuci)->first();
    }

    // Method untuk mengupdate status
    public function updateStatus($id_pengeringan, $status)
    {
        return $this->update($id_pengeringan, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Method untuk mendapatkan data berdasarkan ID cuci
    public function getByIdCuci($id_cuci)
    {
        return $this->where('id_cuci', $id_cuci)->first();
    }

    // Method untuk mendapatkan data berdasarkan ID timbangan
    public function getByIdTimbangan($id_timbangan)
    {
        return $this->where('id_timbangan', $id_timbangan)->first();
    }
}
