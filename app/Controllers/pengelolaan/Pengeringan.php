<?php

namespace App\Controllers\Pengelolaan;

use App\Controllers\BaseController;
use App\Models\PengeringanModel;
use App\Models\PencucianModel;
use App\Models\PenyetrikaanModel;

class Pengeringan extends BaseController
{
    protected $pengeringanModel;
    protected $pencucianModel;
    protected $penyetrikaanModel;
    protected $db;

    public function __construct()
    {
        $this->pengeringanModel = new PengeringanModel();
        $this->pencucianModel = new PencucianModel();
        $this->penyetrikaanModel = new PenyetrikaanModel();
    }

    public function index()
    {
        $pengeringan = $this->pengeringanModel->getPengeringanDetails();
        // Sesuaikan query dengan struktur tabel yang benar

        $data = [
            'title' => 'Pengeringan | Laundry',
            'pengeringan' => $pengeringan
        ];

        // Tambahkan debugging untuk membantu troubleshooting

        return view('pengelola/pengeringan', $data);
    }

    public function start($id_pengeringan)
    {
        try {
            $this->db->transStart();

            $this->pengeringanModel->update($id_pengeringan, [
                'status' => 'in_progress',
                'tanggal_mulai' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memulai proses pengeringan');
            }

            return redirect()->back()->with('message', 'Proses pengeringan dimulai');
        } catch (\Exception $e) {
            log_message('error', 'Error in start pengeringan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulai pengeringan');
        }
    }

    public function StatMove($id_pengeringan)
    {
        try {
            $this->db->transStart();

            $this->pengeringanModel->update($id_pengeringan, [
                'status' => 'ready_move',
                'tanggal_selesai' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menyelesaikan proses pengeringan');
            }

            return redirect()->back()->with('message', 'Proses pengeringan selesai');
        } catch (\Exception $e) {
            log_message('error', 'Error in complete pengeringan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyelesaikan pengeringan');
        }
    }
    public function move($id_pengeringan)
    {
        //ambil data pengeringan berdasarkan id pengeringan
        $pengeringan = $this->pengeringanModel->find($id_pengeringan);

        if (!$pengeringan) {
            return redirect()->back()->with('error', 'Data pengeringan tidak ditemukan');
        }
        // Cek apakah data sudah ada di pengeringan
        $existingPenyetrikaan = $this->penyetrikaanModel->where('id_pengeringan', $id_pengeringan)->first();


        if ($existingPenyetrikaan) {
            return redirect()->back()->with('error', 'Data sudah ada di penyetrikaan');
        }
        try {

            $this->db->transStart();
            $data = [
                'id_pengeringan' => $id_pengeringan,
                'tanggal_mulai' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            //masukan data ke table penyetrikaan
            $inserted = $this->penyetrikaanModel->insert($data);

            // update status pengeringan    
            $this->pengeringanModel->update(
                $id_pengeringan,
                [
                    'status' => 'completed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );

            //complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat memindahkan data');
            }

            return redirect()->to('pengelolaan/pengeringan')->with('message', 'Data berhasil di pindahkan');
        } catch (\Exception $e) {
            log_message('error', 'error in move method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi Kesalahan' . $e->getMessage());
        }
    }
}
