<?php

namespace App\Controllers\Pengelolaan;

use App\Controllers\BaseController;


class Pengelola extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Pengelola || LA-DUS',
        ];

        return view('pengelola/pengelola', $data);
    }
}
