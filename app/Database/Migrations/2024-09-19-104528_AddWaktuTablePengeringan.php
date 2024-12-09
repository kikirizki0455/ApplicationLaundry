<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePengeringanTimer extends Migration
{
    public function up()
    {
        // Menambahkan kolom 'waktu' ke tabel 'pengeringan'
        // $this->forge->addColumn('pengeringan', [
        //     'waktu' => [
        //         'type' => 'TIME',
        //         'null' => true,  // Sesuaikan dengan kebutuhan; bisa juga false jika kolom wajib diisi
        //     ],
        // ]);

        // $this->forge->modifyColumn('pengeringan', [
        //     'status' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['pending', 'in_progress', 'completed'],
        //         'default' => 'in_progress',
        //     ]
        // ]);

        // $this->forge->addColumn('pengeringan', [
        //     'waktu_mulai' => [
        //         'type' => 'DATETIME',
        //         'null' => true,
        //     ],
        //     'waktu_selesai' => [
        //         'type' => 'DATETIME',
        //         'null' => true,
        //     ],
        // ]);
    }

    public function down()
    {
        // $this->forge->dropColumn('pengeringan', ['waktu_mulai', 'waktu_selesai', 'durasi']);
    }
}
