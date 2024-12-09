<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $allowedFields = [
        'id_timbangan_bersih',
        'signature_path',
        'status',
        'created_at'
    ];
}
