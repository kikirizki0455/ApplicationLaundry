<?php

namespace App\Controllers\Distribusi;

use App\Controllers\BaseController;

class Distribusi extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Distribusi | Laundry',
        ];
        return view('distribusi/distribusi', $data);
    }
}
