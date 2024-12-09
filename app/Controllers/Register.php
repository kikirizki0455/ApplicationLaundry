<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Register extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Register | Laundry',
        ];
        return view('register', $data);
    }
}
