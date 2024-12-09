<?php

namespace App\Controllers\Pengelolaan;

use App\Controllers\BaseController;
use App\Models\PenyetrikaanModel;
use App\Models\PengeringanModel;
use App\Models\PelipatanModel;

use PHPUnit\Framework\Attributes\WithoutErrorHandler;

class Penyetrikaan extends BaseController
{
    protected $penyetrikaanModel, $pengeringanModel, $pelipatanModel;
    protected $db;

    public function __construct()
    {
        $this->penyetrikaanModel = new PenyetrikaanModel();
        $this->pengeringanModel = new PengeringanModel();
        $this->pelipatanModel = new PelipatanModel;
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $penyetrikaan = $this->penyetrikaanModel->getPenyetrikaanDetails();

        $data = [
            'title' => 'Penyetrikaan | Laundry',
            'penyetrikaan' => $penyetrikaan,
        ];
        return view('pengelola/penyetrikaan', $data);
    }

    public function start($id_penyetrikaan)
    {

        try {
            $this->db->transStart();

            $this->penyetrikaanModel->update($id_penyetrikaan, [
                'status' => 'in_progress',
                'tanggal_mulai' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memulai proses penyetrikaan');
            }

            return redirect()->back()->with('message', 'Proses penyetrikaan dimulai');
        } catch (\Exception $e) {
            log_message('error', 'Error in start penyetrikaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulai penyetrikaan');
        }
    }

    public function StatMove($id_penyetrikaan)
    {
        try {
            $this->db->transStart();

            $this->penyetrikaanModel->update($id_penyetrikaan, [
                'status' => 'ready_move',
                'tanggal_selesai' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menyelesaikan proses penyetrikaan');
            }

            return redirect()->back()->with('message', 'Proses penyetrikaan selesai dan siap di pindahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error in complete penyetrikaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyelesaikan penyetrikaan');
        }
    }
    public function move($id_penyetrikaan)
    {
        $penyetrikaan = $this->penyetrikaanModel->find($id_penyetrikaan);

        if (!$penyetrikaan) {
            return redirect()->back()->with('error', 'Data penyetrikaan tidak ditemukan');
        }

        $existingPelipatan = $this->pelipatanModel->where('id_penyetrikaan', $id_penyetrikaan);


        try {
            //memulai transaksi table
            $this->db->transStart();
            $data =
                [
                    'id_penyetrikaan' => $id_penyetrikaan,
                    'tanggal_mulai' => date('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            $inserted = $this->pelipatanModel->insert($data);


            // update data penyetrikaan
            $this->penyetrikaanModel->update($id_penyetrikaan, [
                'status' => 'completed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat memindahkan data');
            }

            return redirect()->to('pengelolaan/penyetrikaan')->with('message', 'Data berhasil di pindahkan');
        } catch (\Exception $e) {
            log_message('error', 'error in move method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan' . $e->getMessage());
        }
    }
}
