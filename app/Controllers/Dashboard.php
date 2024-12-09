<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Pengelolaan\Pelipatan;
use App\Models\PencucianModel;
use App\Models\PenggunaanBahanModel;
use App\Models\PelipatanModel;

class Dashboard extends BaseController
{

    protected $pencucianModel, $penggunaanBahanModel, $pelipatanModel;

    public function __construct()
    {
        $this->pelipatanModel = new PelipatanModel();
        $this->pencucianModel = new PencucianModel();
        $this->penggunaanBahanModel = new PenggunaanBahanModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $salesData = [12, 19, 3, 5, 2, 3, 12]; // Misalnya, data penjualan per bulan
        // Ambil data bahan
        $builderBahan = $db->table('bahan');
        $queryBahan = $builderBahan->get();
        $bahan = $queryBahan->getResult();

        // Ambil data barang
        $builderBarang = $db->table('barang');
        $queryBarang = $builderBarang->get();
        $barang = $queryBarang->getResult();

        // Ambil data pegawai
        $builderPegawai = $db->table('pegawai');
        $queryPegawai = $builderPegawai->get();
        $pegawai = $queryPegawai->getResult();

        // Kelompokkan bahan kritis
        $bahan_kritis = array_filter($bahan, function ($b) {
            return $b->stok_bahan < 12;
        });

        $pencucian = $this->pencucianModel->findAll();


        // Ambil bulan dan tahun saat ini
        // Ambil bulan dan tahun saat ini
        $bulan = date('m');
        $tahun = date('Y');

        // Ambil tanggal hari ini
        $tanggal_hari_ini = date('Y-m-d');
        $penggunaan_bahan_hari_ini = $this->penggunaanBahanModel->getPenggunaanBahanPerHari($tanggal_hari_ini);
        $penggunaan_bahan_hari_ini = $penggunaan_bahan_hari_ini['jumlah_penggunaan'] ?? 0;


        // Ambil tanggal kemarin
        $tanggal_kemarin = date('Y-m-d', strtotime('yesterday'));
        $penggunaan_bahan_kemarin = $this->penggunaanBahanModel->getPenggunaanBahanPerHari($tanggal_kemarin);
        $penggunaan_bahan_kemarin = $penggunaan_bahan_kemarin['jumlah_penggunaan'] ?? 0;
        // Menghitung persentase penggunaan bahan hari ini dibandingkan dengan kemarin
        $persentase_hari_ini = ($penggunaan_bahan_hari_ini - $penggunaan_bahan_kemarin) / ($penggunaan_bahan_kemarin ?: 1) * 100;

        // Ambil data penggunaan bahan untuk minggu ini
        $startDate = date('Y-m-d', strtotime('last sunday'));
        $endDate = date('Y-m-d');
        $penggunaan_bahan_minggu_ini = $this->penggunaanBahanModel->getPenggunaanBahanPerMinggu($startDate, $endDate);
        $penggunaan_bahan_minggu_ini = $penggunaan_bahan_minggu_ini['jumlah_penggunaan'] ?? 0;

        // Ambil data penggunaan bahan untuk minggu lalu
        $startDateMingguLalu = date('Y-m-d', strtotime('last sunday - 7 days'));
        $endDateMingguLalu = date('Y-m-d', strtotime('last saturday - 7 days'));
        $penggunaan_bahan_minggu_lalu = $this->penggunaanBahanModel->getPenggunaanBahanPerMinggu($startDateMingguLalu, $endDateMingguLalu);
        $penggunaan_bahan_minggu_lalu = $penggunaan_bahan_minggu_lalu['jumlah_penggunaan'] ?? 0;

        // Menghitung persentase penggunaan bahan minggu ini dibandingkan dengan minggu lalu
        $persentase_minggu_ini = ($penggunaan_bahan_minggu_ini - $penggunaan_bahan_minggu_lalu) / ($penggunaan_bahan_minggu_lalu ?: 1) * 100;


        // Ambil data penggunaan bahan untuk bulan ini
        $penggunaan_bahan_bulan_ini = $this->penggunaanBahanModel->getPenggunaanBahanPerBulan($bulan, $tahun);
        $penggunaan_bahan_bulan_ini = $penggunaan_bahan_bulan_ini['jumlah_penggunaan'] ?? 0;

        // Ambil data penggunaan bahan untuk bulan lalu
        $bulan_lalu = date('m', strtotime('last month'));
        $tahun_lalu = date('Y', strtotime('last month'));
        $penggunaan_bahan_bulan_lalu = $this->penggunaanBahanModel->getPenggunaanBahanPerBulan($bulan_lalu, $tahun_lalu);
        $penggunaan_bahan_bulan_lalu = $penggunaan_bahan_bulan_lalu['jumlah_penggunaan'] ?? 0;

        // Menghitung persentase penggunaan bahan bulan ini dibandingkan dengan bulan lalu
        $persentase_bulan_ini = ($penggunaan_bahan_bulan_ini - $penggunaan_bahan_bulan_lalu) / ($penggunaan_bahan_bulan_lalu ?: 1) * 100;

        // Ambil data penggunaan bahan untuk tahun ini
        $penggunaan_bahan_tahun_ini = $this->penggunaanBahanModel->getPenggunaanBahanPerTahun($tahun);
        $penggunaan_bahan_tahun_ini = $penggunaan_bahan_tahun_ini['jumlah_penggunaan'] ?? 0;

        // Ambil data penggunaan bahan untuk tahun lalu
        $penggunaan_bahan_tahun_lalu = $this->penggunaanBahanModel->getPenggunaanBahanPerTahun($tahun - 1);
        $penggunaan_bahan_tahun_lalu = $penggunaan_bahan_tahun_lalu['jumlah_penggunaan'] ?? 0;

        // Menghitung persentase penggunaan bahan tahun ini dibandingkan dengan tahun lalu
        $persentase_tahun_ini = ($penggunaan_bahan_tahun_ini - $penggunaan_bahan_tahun_lalu) / ($penggunaan_bahan_tahun_lalu ?: 1) * 100;


        $penggunaanBahan = $this->penggunaanBahanModel->get_penggunaan_bahan_per_bulan($bulan, $tahun);

        $dailyUsage = $this->penggunaanBahanModel->getPenggunaanBahanPerHari(date('Y-m-d'));
        $weeklyUsage = $this->penggunaanBahanModel->getPenggunaanBahanPerMinggu(date('Y-m-d', strtotime('last sunday')), date('Y-m-d'));
        $monthlyUsage = $this->penggunaanBahanModel->getPenggunaanBahanPerBulan(date('m'), date('Y'));
        $yearlyUsage = $this->penggunaanBahanModel->getPenggunaanBahanPerTahun(date('Y'));

        // Mengelompokkan bahan kritis berdasarkan ID bahan
        $bahan_kritis_unique = [];
        foreach ($bahan_kritis as $b) {
            if (!isset($bahan_kritis_unique[$b->id_bahan])) {
                $bahan_kritis_unique[$b->id_bahan] = $b;
            }
        }

        $pelipatan = $this->pelipatanModel->findAll();

        $data = [
            'title' => 'Dashboard | Laundry',
            'pencucian' => $pencucian,
            'bahan' => $bahan,
            'pelipatan' => $pelipatan,
            'barang' => $barang,
            'pegawai' => $pegawai,
            'bahan_kritis' => $bahan_kritis_unique, // Kirimkan bahan kritis unik
            'modalBahan' => session()->get('modalBahan'), // Ambil data modal dari session
            'modalBahanId' => session()->get('modalBahanId'), // Ambil id bahan dari session
            'salesData' => $salesData,
            'penggunaanBahan' => $penggunaanBahan,
            'penggunaan_bahan_hari_ini' => $penggunaan_bahan_hari_ini,
            'penggunaan_bahan_minggu_ini' => $penggunaan_bahan_minggu_ini,
            'penggunaan_bahan_bulan_ini' => $penggunaan_bahan_bulan_ini,
            'penggunaan_bahan_tahun_ini' => $penggunaan_bahan_tahun_ini,
            'persentase_hari_ini'  => $persentase_hari_ini,
            'persentase_minggu_ini' => $persentase_minggu_ini,
            'persentase_bulan_ini' => $persentase_bulan_ini,
            'persentase_tahun_ini' => $persentase_tahun_ini,
            'daily' => $dailyUsage,
            'weekly' => $weeklyUsage,
            'monthly' => $monthlyUsage,
            'yearly' => $yearlyUsage

        ];


        // Hapus data modal dari session setelah digunakan
        session()->remove('modalBahan');
        session()->remove('modalBahanId');

        return view('dashboard', $data);
    }



    public function tambah_stok()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('bahan'); // Nama tabel bahan

        $idBahan = $this->request->getPost('id_bahan');
        $stokTambah = $this->request->getPost('stok_tambah');

        if ($idBahan && $stokTambah) {
            // Cek apakah bahan ada di database
            $builder->where('id_bahan', $idBahan);
            $bahan = $builder->get()->getRow();

            if ($bahan) {
                $newStok = $bahan->stok_bahan + $stokTambah;

                // Update stok bahan
                $builder->where('id_bahan', $idBahan);
                $builder->update(['stok_bahan' => $newStok]);

                return redirect()->to('/dashboard')->with('message', 'Stok berhasil diperbarui');
            } else {
                return redirect()->to('/dashboard')->with('error', 'Bahan tidak ditemukan');
            }
        }

        return redirect()->to('/dashboard')->with('error', 'Gagal memperbarui stok');
    }

    public function show_modal($bahanId)
    {
        // Ambil data bahan dari database
        $db = \Config\Database::connect();
        $builder = $db->table('bahan');
        $builder->where('id_bahan', $bahanId);
        $bahan = $builder->get()->getRow();

        if ($bahan) {
            // Simpan data modal di session
            session()->set([
                'modalBahan' => $bahan->nama_bahan,
                'modalBahanId' => $bahan->id_bahan
            ]);
        }

        return redirect()->to('/dashboard');
    }
}
