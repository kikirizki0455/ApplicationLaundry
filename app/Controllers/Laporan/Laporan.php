<?php

namespace App\Controllers\Laporan;

use App\Controllers\BaseController;

use App\Models\TimbanganBersihModel;
use TCPDF;

class Laporan extends BaseController
{
    protected $timbanganBersihModel;

    public function __construct()
    {
        $this->timbanganBersihModel = new TimbanganBersihModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Distribusi | LA-DUS',
        ];

        return view('laporan/laporan', $data);
    }
}
