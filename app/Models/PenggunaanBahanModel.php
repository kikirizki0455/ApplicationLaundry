<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaanBahanModel extends Model
{
    // Nama tabel
    protected $table      = 'penggunaan_bahan';
    protected $primaryKey = 'id_penggunaan_bahan';

    // Kolom yang bisa diisi
    protected $allowedFields = ['id_bahan', 'id_timbangan', 'jumlah_penggunaan', 'tanggal'];

    // Opsi untuk timestamps (created_at, updated_at)
    protected $useTimestamps = true;

    // Mengambil data penggunaan bahan berdasarkan ID timbangan
    public function get_penggunaan_bahan_by_timbangan($id_timbangan)
    {
        return $this->db->table($this->table)
            ->join('bahan', 'bahan.id_bahan = penggunaan_bahan.id_bahan')
            ->where('penggunaan_bahan.id_timbangan', $id_timbangan)
            ->get()
            ->getResult();  // Mengembalikan hasil query
    }

    // Menambahkan penggunaan bahan baru
    public function add_penggunaan_bahan($data)
    {
        return $this->insert($data);  // Menambahkan data ke tabel
    }

    // Mengambil penggunaan bahan per bulan
    public function get_penggunaan_bahan_per_bulan($bulan, $tahun)
    {
        // Mengambil data penggunaan bahan per hari, bulan, dan tahun
        $data = $this->db->table($this->table)
            ->select('bahan.nama_bahan, DATE(penggunaan_bahan.tanggal) AS tanggal, SUM(penggunaan_bahan.jumlah_penggunaan) AS total_penggunaan')
            ->join('bahan', 'bahan.id_bahan = penggunaan_bahan.id_bahan')
            ->where('MONTH(penggunaan_bahan.tanggal)', $bulan)
            ->where('YEAR(penggunaan_bahan.tanggal)', $tahun)
            ->groupBy('bahan.id_bahan, DATE(penggunaan_bahan.tanggal)')  // Mengelompokkan berdasarkan bahan dan tanggal
            ->get()
            ->getResult();  // Mengembalikan hasil query

        // Mengolah data untuk chart
        $labels = [];
        $totalUsage = [];

        foreach ($data as $item) {
            $labels[] = $item->nama_bahan . ' - ' . $item->tanggal;  // Gabungkan nama bahan dengan tanggal
            $totalUsage[] = $item->total_penggunaan;  // Total penggunaan bahan per bahan
        }

        // Mengembalikan data dalam format yang siap untuk chart
        return [
            'labels' => $labels,
            'totalUsage' => $totalUsage
        ];
    }


    // Mengambil semua data penggunaan bahan
    public function get_all_penggunaan_bahan()
    {
        return $this->findAll();  // Mengambil semua data dari tabel
    }

    // Mengupdate data penggunaan bahan
    public function update_penggunaan_bahan($id_penggunaan_bahan, $data)
    {
        return $this->update($id_penggunaan_bahan, $data);  // Update data berdasarkan ID
    }

    // Menghapus data penggunaan bahan
    public function delete_penggunaan_bahan($id_penggunaan_bahan)
    {
        return $this->delete($id_penggunaan_bahan);  // Menghapus data berdasarkan ID
    }



    public function getPenggunaanBahanPerHari($tanggal)
    {
        return $this->where('tanggal >=', $tanggal)
            ->selectSum('jumlah_penggunaan')
            ->first();
    }

    public function getPenggunaanBahanPerMinggu($startDate, $endDate)
    {
        return $this->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->selectSum('jumlah_penggunaan')
            ->first();
    }

    public function getPenggunaanBahanPerBulan($bulan, $tahun)
    {
        return $this->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah_penggunaan')
            ->first();
    }

    public function getPenggunaanBahanPerTahun($tahun)
    {
        return $this->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah_penggunaan')
            ->first();
    }
}
