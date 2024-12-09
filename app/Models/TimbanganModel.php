<?php

namespace App\Models;

use CodeIgniter\Model;

class TimbanganModel extends Model
{
    protected $table = 'timbangan';
    protected $primaryKey = 'id_timbangan';
    protected $allowedFields = ['berat_barang', 'id_ruangan', 'no_invoice', 'status', 'id_barang', 'id_pegawai', 'id_mesin', 'pencucian_status', 'updated_at'];




    // Pada TimbanganModel.php

    public function getTimbanganKotor()
    {
        $builder = $this->db->table('timbangan')
            ->select('timbangan.*, pegawai.nama_pegawai, mesin_cuci.nama_mesin, ruangan.nama_ruangan')
            ->join('pegawai', 'pegawai.id_pegawai = timbangan.id_pegawai', 'left')
            ->join('mesin_cuci', 'mesin_cuci.id_mesin = timbangan.id_mesin', 'left')
            ->join('ruangan', 'ruangan.id_ruangan = timbangan.id_ruangan', 'left')
            ->where('timbangan.status', 'pending');

        $timbanganData = $builder->get()->getResult();

        // Fetch data dari timbangan_barang untuk setiap timbangan
        foreach ($timbanganData as &$timbangan) {
            // Menambahkan data timbangan_barang berdasarkan id_timbangan
            $timbanganBarangDetails = $this->db->table('timbangan_barang as tb')
                ->select('tb.nama_barang, tb.jumlah')
                ->where('tb.id_timbangan', $timbangan->id_timbangan)
                ->get()
                ->getResult();

            // Menyimpan hasil timbangan_barang pada objek timbangan
            $timbangan->barang_details = $timbanganBarangDetails;
        }

        return $timbanganData;
    }



    public function getTimbanganKotorById($id)
    {
        // Query untuk mendapatkan timbangan berdasarkan ID
        $builder = $this->db->table('timbangan')
            ->select('timbangan.*, pegawai.nama_pegawai, mesin_cuci.nama_mesin, ruangan.nama_ruangan')
            ->join('pegawai', 'pegawai.id_pegawai = timbangan.id_pegawai', 'left')
            ->join('mesin_cuci', 'mesin_cuci.id_mesin = timbangan.id_mesin', 'left')
            ->join('ruangan', 'ruangan.id_ruangan = timbangan.id_ruangan', 'left')
            ->where('timbangan.id_timbangan', $id)
            ->where('timbangan.status', 'pending');

        $timbangan = $builder->get()->getRow();

        // Jika data timbangan ditemukan, ambil juga data timbangan_barang
        if ($timbangan) {
            $timbanganBarangDetails = $this->db->table('timbangan_barang as tb')
                ->select('tb.nama_barang, tb.jumlah')
                ->where('tb.id_timbangan', $timbangan->id_timbangan)
                ->get()
                ->getResult();

            // Tambahkan detail barang ke objek timbangan
            $timbangan->barang_details = $timbanganBarangDetails;
        }

        return $timbangan;
    }
}
