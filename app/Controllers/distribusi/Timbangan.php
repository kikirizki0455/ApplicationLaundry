<?php

namespace App\Controllers\Distribusi;

use App\Controllers\BaseController;
use CodeIgniter\Database\Config;
use App\Models\PegawaiModel;
use App\Models\MesinCuciModel;
use App\Models\TimbanganModel;
use App\Models\NoInvoiceModel;
use App\Models\BahanModel;
use App\Models\MesinBahanModel;
use App\Models\RuanganModel;
use App\Models\TimbanganBarangModel;
use App\Models\PencucianModel;


class Timbangan extends BaseController
{
    protected $builderTimbangan;
    protected $builderPencucian;
    protected $builderBarang;
    protected $builderBahan;
    protected $builderMesinCuci;
    protected $timbanganBarangModel;
    protected $noInvoiceModel;
    protected $pencucianModel;


    protected $pegawaiModel;
    protected $mesinCuciModel;
    protected $timbanganModel;
    protected $bahanModel;
    protected $mesinBahanModel;
    protected $ruanganModel;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
        $this->mesinCuciModel = new MesinCuciModel();
        $this->timbanganModel = new TimbanganModel();
        $this->bahanModel = new BahanModel();
        $this->mesinBahanModel = new MesinBahanModel();
        $this->ruanganModel  = new RuanganModel();
        $this->timbanganBarangModel = new TimbanganBarangModel();
        $this->noInvoiceModel = new NoInvoiceModel();
        $this->pencucianModel = new PencucianModel();

        $db = Config::connect();
        $this->builderTimbangan = $db->table('timbangan');
        $this->builderPencucian = $db->table('pencucian');
        $this->builderBarang = $db->table('barang');
        $this->builderBahan = $db->table('bahan');
    }

    public function tambah_timbangan()
    {
        if ($this->request->getMethod() === 'post') {

            //mengambil data id mesin
            $id_mesin = $this->request->getPost('id_mesin');
            //update status mesin cuci
            $this->mesinCuciModel->updateMesinCuciStatus($id_mesin, 'tidak aktif');
        }

        $ruangan = $this->ruanganModel->getBarang();
        $role = 'pengelola';
        $state = 'aktif';
        $data = [
            'title' => 'Tambah timbangan | Laundry',
            'pegawai' => $this->pegawaiModel->getPegawaiByRole($role),
            'mesin_cuci'  => $this->mesinCuciModel->getMesinByStatus($state),
            'barang' => $this->getBarangList(),
            'ruangan' => $ruangan
        ];


        return view('/distribusi/tambah_timbangan', $data);
    }

    public function timbangan_kotor()
    {
        $this->builderTimbangan->where('status', 'pending');
        $queryDataTimbangan = $this->builderTimbangan->get();
        $timbangan = $this->timbanganModel->getTimbanganKotor();


        $builderBarang = $this->builderBarang; // Menggunakan builder yang telah didefinisikan
        $queryBarang = $builderBarang->get();
        $barang = $queryBarang->getResult();


        $ruangan = $this->ruanganModel->getBarang();

        $data = [
            'title' => 'Timbangan | Laundry',
            'timbangan' => $timbangan,
            'ruangan' => $ruangan
        ];


        return view('/distribusi/timbangan_kotor', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        // Ambil data dari form
        $idRuangan = $this->request->getPost('id_ruangan');
        $idPegawai = $this->request->getPost('id_pegawai');
        $idMesin = $this->request->getPost('id_mesin');
        $beratBarang = $this->request->getPost('berat_barang');
        $barangList = $this->request->getPost('barang');

        // Validasi apakah ada timbangan pada ruangan yang sama dengan status 'pending' atau 'in_progress'
        $existingTimbangan = $db->table('timbangan')
            ->where('id_ruangan', $idRuangan)
            ->whereIn('status', ['pending', 'in_progress'])
            ->get()
            ->getFirstRow();
        // Jika ada timbangan dengan status 'pending' pada ruangan yang sama, tampilkan pesan error
        if ($existingTimbangan && $existingTimbangan->status == 'pending') {
            session()->setFlashdata('error', 'Approve timbangan sebelumnya untuk memasukan ruangan ini.');
            return redirect()->back()->withInput();
        }

        // Jika ada timbangan dengan status 'in_progress' pada ruangan yang sama, lanjutkan proses
        if ($existingTimbangan && $existingTimbangan->status == 'in_progress') {
            // Validasi stok barang
            foreach ($barangList as $barang) {
                // Pastikan ada pemeriksaan keys sebelum mengaksesnya
                if (!isset($barang['id_barang']) || !isset($barang['nama_barang'])) {
                    session()->setFlashdata('error', 'Data barang tidak lengkap.');
                    return redirect()->back()->withInput();
                }

                // Ambil stok barang dari ruangan
                $stokBarang = $db->table('ruangan_barang')
                    ->select('jumlah')
                    ->where('id_ruangan', $idRuangan)
                    ->where('id_barang', $barang['id_barang'])
                    ->get()
                    ->getRow();

                if (!$stokBarang) {
                    session()->setFlashdata('error', 'Barang ' . $barang['nama_barang'] . ' tidak ditemukan di ruangan.');
                    return redirect()->back()->withInput();
                }

                // Cek apakah jumlah barang diinputkan dan valid
                $stokBarangJumlah = is_numeric($stokBarang->jumlah) ? (int) $stokBarang->jumlah : 0;
                $barangJumlah = isset($barang['jumlah']) && is_numeric($barang['jumlah']) ? (int) $barang['jumlah'] : 0;

                // Cek apakah jumlah barang melebihi stok yang ada
                if ($barangJumlah > $stokBarangJumlah) {
                    session()->setFlashdata('error', 'Jumlah ' . $barang['nama_barang'] . ' melebihi stok yang tersedia (' . $stokBarangJumlah . ').');
                    return redirect()->back()->withInput();
                }

                // Update stok barang di ruangan
                $newStok = $stokBarangJumlah - $barangJumlah;
                $db->table('ruangan_barang')
                    ->where('id_ruangan', $idRuangan)
                    ->where('id_barang', $barang['id_barang'])
                    ->update(['jumlah' => $newStok]);
            }

            // Generate nomor invoice
            $noInvoice = $this->noInvoiceModel->generateInvoiceNo();

            // Simpan data ke tabel `timbangan`
            $timbanganData = [
                'id_pegawai' => $idPegawai,
                'id_ruangan' => $idRuangan,
                'id_mesin'   => $idMesin,
                'berat_barang' => $beratBarang,
                'no_invoice'    => $noInvoice,
                'status' => 'pending' // Status tetap 'in_progress'
            ];



            $db->table('timbangan')->insert($timbanganData);
            $idTimbangan = $db->insertID(); // Ambil ID yang baru disimpan di `timbangan`

            // Simpan data ke tabel `timbangan_barang`
            foreach ($barangList as $barang) {
                // Gunakan nilai default 0 jika jumlah barang tidak diinputkan
                $barangJumlah = isset($barang['jumlah']) && is_numeric($barang['jumlah']) ? (int) $barang['jumlah'] : 0;

                // Jika jumlah barang lebih dari 0, simpan ke timbangan_barang
                if ($barangJumlah > 0) {
                    $timbanganBarangData = [
                        'id_timbangan' => $idTimbangan,
                        'id_barang' => $barang['id_barang'],
                        'nama_barang'  => $barang['nama_barang'],
                        'jumlah'       => $barangJumlah
                    ];
                    $db->table('timbangan_barang')->insert($timbanganBarangData);

                    // Update stok barang yang dicuci di tabel barang
                    $db->table('barang')
                        ->where('id_barang', $barang['id_barang'])
                        ->set('stok_dicuci', 'stok_dicuci + ' . $barangJumlah, false)
                        ->update();
                }
            }

            // Redirect dengan pesan sukses
            session()->setFlashdata('message', 'Timbangan berhasil ditambahkan.');
            return redirect()->to('/distribusi/timbangan_kotor');
        } else {
            // Jika tidak ada timbangan dengan status 'pending' atau 'in_progress', data baru dapat ditambahkan
            // Validasi stok barang
            foreach ($barangList as $barang) {
                // Pastikan ada pemeriksaan keys sebelum mengaksesnya
                if (!isset($barang['id_barang']) || !isset($barang['nama_barang'])) {
                    session()->setFlashdata('error', 'Data barang tidak lengkap.');
                    return redirect()->back()->withInput();
                }

                // Ambil stok barang dari ruangan
                $stokBarang = $db->table('ruangan_barang')
                    ->select('jumlah')
                    ->where('id_ruangan', $idRuangan)
                    ->where('id_barang', $barang['id_barang'])
                    ->get()
                    ->getRow();

                if (!$stokBarang) {
                    session()->setFlashdata('error', 'Barang ' . $barang['nama_barang'] . ' tidak ditemukan di ruangan.');
                    return redirect()->back()->withInput();
                }

                // Cek apakah jumlah barang diinputkan dan valid
                $stokBarangJumlah = is_numeric($stokBarang->jumlah) ? (int) $stokBarang->jumlah : 0;
                $barangJumlah = isset($barang['jumlah']) && is_numeric($barang['jumlah']) ? (int) $barang['jumlah'] : 0;

                // Cek apakah jumlah barang melebihi stok yang ada
                if ($barangJumlah > $stokBarangJumlah) {
                    session()->setFlashdata('error', 'Jumlah ' . $barang['nama_barang'] . ' melebihi stok yang tersedia (' . $stokBarangJumlah . ').');
                    return redirect()->back()->withInput();
                }

                // Update stok barang di ruangan
                $newStok = $stokBarangJumlah - $barangJumlah;
                $db->table('ruangan_barang')
                    ->where('id_ruangan', $idRuangan)
                    ->where('id_barang', $barang['id_barang'])
                    ->update(['jumlah' => $newStok]);
            }

            // Generate nomor invoice
            $noInvoice = $this->noInvoiceModel->generateInvoiceNo();

            // Simpan data ke tabel `timbangan`
            $timbanganData = [
                'id_pegawai' => $idPegawai,
                'id_ruangan' => $idRuangan,
                'id_mesin'   => $idMesin,
                'berat_barang' => $beratBarang,
                'no_invoice'    => $noInvoice,
                'status' => 'pending' // Status tetap 'in_progress'
            ];

            $db->table('timbangan')->insert($timbanganData);
            $idTimbangan = $db->insertID(); // Ambil ID yang baru disimpan di `timbangan`

            // Simpan data ke tabel `timbangan_barang`
            foreach ($barangList as $barang) {
                // Gunakan nilai default 0 jika jumlah barang tidak diinputkan
                $barangJumlah = isset($barang['jumlah']) && is_numeric($barang['jumlah']) ? (int) $barang['jumlah'] : 0;

                // Jika jumlah barang lebih dari 0, simpan ke timbangan_barang
                if ($barangJumlah > 0) {
                    $timbanganBarangData = [
                        'id_timbangan' => $idTimbangan,
                        'id_barang' => $barang['id_barang'],
                        'nama_barang'  => $barang['nama_barang'],
                        'jumlah'       => $barangJumlah
                    ];
                    $db->table('timbangan_barang')->insert($timbanganBarangData);

                    // Update stok barang yang dicuci di tabel barang
                    $db->table('barang')
                        ->where('id_barang', $barang['id_barang'])
                        ->set('stok_dicuci', 'stok_dicuci + ' . $barangJumlah, false)
                        ->update();
                }
            }

            // Redirect dengan pesan sukses
            session()->setFlashdata('message', 'Timbangan berhasil ditambahkan.');
            return redirect()->to('/distribusi/timbangan_kotor');
        }
    }





    public function delete_timbangan_kotor($id)
    {
        $this->builderTimbangan->where('id_timbangan', $id)->delete();
        return redirect()->to('/distribusi/timbangan_kotor')->with('message', 'Data berhasil dihapus');
    }



    public function approve($id)
    {
        // Mulai transaksi database untuk memastikan konsistensi data
        $this->db->transStart();

        try {
            // Mengambil data timbangan yang akan diapprove
            $timbangan = $this->timbanganModel->find($id);

            if (!$timbangan) {
                throw new \Exception('Data timbangan tidak ditemukan');
            }

            if ($timbangan['status'] !== 'pending') {
                throw new \Exception('Timbangan sudah tidak bisa diapprove');
            }

            // Ambil data bahan untuk mesin cuci
            $id_mesin_cuci = $timbangan['id_mesin'];
            $mesin_bahan_list = $this->mesinBahanModel->where('id_mesin', $id_mesin_cuci)->findAll();

            // Validasi dan kurangi stok bahan
            foreach ($mesin_bahan_list as $mesin_bahan) {
                $id_bahan = $mesin_bahan['id_bahan'];
                $jumlahDiMesin = $mesin_bahan['jumlah_bahan'];
                $bahan = $this->bahanModel->find($id_bahan);

                if (!$bahan) {
                    throw new \Exception('Data bahan tidak ditemukan');
                }

                $stokBahan = intval($bahan['stok_bahan']);
                if ($stokBahan < $jumlahDiMesin) {
                    throw new \Exception('Stok bahan tidak cukup');
                }

                // Kurangi stok bahan
                $newStokBahan = $stokBahan - $jumlahDiMesin;
                $this->bahanModel->update($id_bahan, ['stok_bahan' => $newStokBahan]);

                // Simpan penggunaan bahan ke tabel penggunaan_bahan
                $penggunaanData = [
                    'id_bahan'      => $id_bahan,
                    'jumlah_penggunaan'  => $jumlahDiMesin,
                    'id_timbangan'  => $timbangan['id_timbangan'],
                    'tanggal'       => date('Y-m-d'), // Tanggal saat ini
                    'keterangan'    => 'Penggunaan bahan untuk timbangan ID: ' . $timbangan['id_timbangan'],
                    'bulan'         => date('m'), // Bulan saat ini
                    'tahun'         => date('Y'), // Tahun saat ini

                ];

                $inserted = $this->db->table('penggunaan_bahan')->insert($penggunaanData);
            }

            // Update status timbangan menjadi "completed"
            $this->timbanganModel->update($id, ['status' => 'completed']);

            // Ambil data timbangan barang
            $timbangan_barang_list = $this->db->table('timbangan_barang')
                ->where('id_timbangan', $id)
                ->get()
                ->getResult();

            // Siapkan data pencucian
            $pencucianData[] = [
                'id_timbangan'      => $timbangan['id_timbangan'],
                'berat_barang'      => $timbangan['berat_barang'],
                'status'            => 'in_progress',
                'pencucian_status'  => 'pending',
                'tanggal_mulai'     => date('Y-m-d H:i:s'),
                'id_mesin'          => $timbangan['id_mesin'],
            ];

            // Tambahkan pengecekan sebelum insert
            if (empty($pencucianData)) {
                throw new \Exception('Tidak ada data pencucian untuk dimasukkan');
            }

            // Debug: Cetak data yang akan diinsert
            log_message('debug', 'Pencucian Data: ' . json_encode($pencucianData));

            // Gunakan insertBatch untuk efisiensi
            $result = $this->pencucianModel->insertBatch($pencucianData);

            if ($result === false) {
                throw new \Exception('Gagal memasukkan data ke pencucian');
            }

            // Selesaikan transaksi
            $this->db->transComplete();

            return redirect()->to('/distribusi/timbangan_kotor')->with('message', 'Timbangan berhasil diapprove dan dipindahkan ke pencucian.');
        } catch (\Exception $e) {
            // Batalkan transaksi jika ada kesalahan
            $this->db->transRollback();

            // Log error dengan detail lebih lengkap
            log_message('error', 'Approve Error: ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function reject($id)
    {
        $db = \Config\Database::connect();

        // Mulai transaksi database
        $db->transStart();

        try {
            // Mengambil data timbangan yang akan di-reject
            $timbangan = $db->table('timbangan')
                ->where('id_timbangan', $id)
                ->get()
                ->getRow();

            if (!$timbangan) {
                throw new \Exception('Data timbangan tidak ditemukan');
            }

            if ($timbangan->status !== 'pending') {
                throw new \Exception('Timbangan sudah tidak bisa di reject');
            }

            // Ambil daftar barang yang akan dikembalikan
            $barangList = $db->table('timbangan_barang')
                ->where('id_timbangan', $id)
                ->get()
                ->getResultArray();

            // Kembalikan stok di ruangan berdasarkan id_ruangan dan id_barang yang sesuai
            foreach ($barangList as $barang) {
                // Kembalikan stok di ruangan barang yang sesuai
                $db->table('ruangan_barang')
                    ->where('id_ruangan', $timbangan->id_ruangan)
                    ->where('id_barang', $barang['id_barang'])
                    ->set('jumlah', 'jumlah + ' . $barang['jumlah'], false)
                    ->update();

                // Kembalikan stok dicuci ke 0 di tabel barang
                $db->table('barang')
                    ->where('id_barang', $barang['id_barang'])
                    ->set('stok_dicuci', 'stok_dicuci - ' . $barang['jumlah'], false)
                    ->update();
            }

            // Hapus data timbangan terkait
            $db->table('timbangan_barang')
                ->where('id_timbangan', $id)
                ->delete();

            $db->table('timbangan')
                ->where('id_timbangan', $id)
                ->delete();

            // Commit transaksi
            $db->transCommit();

            return redirect()->to('/distribusi/timbangan_kotor')->with('rejectMessage', 'Timbangan berhasil direject');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $db->transRollback();

            // Log error
            log_message('error', 'Reject Timbangan Gagal: ' . $e->getMessage());

            return redirect()->to('/distribusi/timbangan_kotor')->with('errorMessage', $e->getMessage());
        }
    }

    private function getBarangList()
    {
        // Mengambil daftar barang dari tabel barang
        $db = config::connect();
        $builder = $db->table('barang');
        $query = $builder->get();
        return $query->getResult(); // Mengembalikan hasil query sebagai objek
    }
}
