<?php

namespace App\Controllers\Laporan;

use App\Controllers\BaseController;
use App\Models\TimbanganBersihModel;
use TCPDF;

class LaporanDistribusi extends BaseController
{
    protected $timbanganBersihModel;

    public function __construct()
    {
        $this->timbanganBersihModel = new TimbanganBersihModel();
    }

    public function index()
    {
        $perPage = 2;

        // Get current page dari segment URL
        $currentPage = $this->request->getVar('page_distribusi') ?? 1;
        $totalRows = $this->timbanganBersihModel->countAllResults();
        $timbanganBersih = $this->timbanganBersihModel->getTimbanganBersihWithDetails($perPage, ($currentPage - 1) * $perPage);

        // buat pager
        $pager = service('pager');
        $pager->setPath('laporan/laporan-distribusi');
        $pager->makeLinks($currentPage, $perPage, $totalRows, 'default_full');
        $data = [
            'title' => 'Laporan Distribusi | LA-DUS',
            'timbangan_bersih' => $timbanganBersih,
            'pager' => $pager,
            'currentPage' => $currentPage
        ];

        return view('laporan/laporan_distribusi', $data);
    }

    public function detail($id)
    {
        $timbangan = $this->timbanganBersihModel->getTimbanganBersihById($id);
        $detailBarang = $this->timbanganBersihModel->getDetailBarang($id);

        if (!$timbangan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'timbangan' => $timbangan,
                'detailBarang' => $detailBarang
            ]
        ]);
    }
}
