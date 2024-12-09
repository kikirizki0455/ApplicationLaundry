<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UbahIdPencucian extends Migration
{
    public function up()
    {
        // $this->forge->modifyColumn('pengeringan', [
        //     'id_pencucian' => [
        //         'name' => 'id_cuci',
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //     ],
        // ]);
    }

    public function down()
    {
        // $this->forge->modifyColumn('pengeringan', [
        //     'id_cuci' => [
        //         'name' => 'id_pencucian',
        //         'type' => 'INT',
        //         'constraint' => 5
        //     ]
        // ]);
    }
}
