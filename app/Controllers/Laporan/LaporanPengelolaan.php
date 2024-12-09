<?php

namespace App\Controllers\Laporan;

use App\Controllers\BaseController;
use App\Models\DetailPelipatanModel;
use App\Models\PelipatanModel;

class LaporanPengelolaan extends BaseController
{
    protected $pelipatanModel;
    protected $detailPelipatanModel;

    public function __construct()
    {
        $this->pelipatanModel = new PelipatanModel();
        $this->detailPelipatanModel = new DetailPelipatanModel();
    }

    public function index()
    {

        // Ambil data dengan pagination
        $pelipatan = $this->pelipatanModel->getPelipatanDetails();

        // Buat pager

        $data = [
            'title' => 'Laporan Pengelolaan | LA-DUS',
            'pelipatan' => $pelipatan,
        ];

        return view('laporan/laporan_pengelolaan', $data);
    }

    public function detail($id)
    {
        try {
            $pelipatan = $this->pelipatanModel->getPelipatanDetailsById($id);
            if (!$pelipatan) {
                throw new \Exception('Data pelipatan tidak ditemukan');
            }



            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'pelipatan' => $pelipatan,
                    'pelipatan' => $pelipatan
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
