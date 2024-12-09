<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class addDurasiTablePengeringan extends Migration
{
    public function up()
    {
        // $this->forge->dropForeignKey('pengeringan', 'pengeringan_id_bahan_foreign');

        // Menghapus kolom id_bahan dari tabel pengeringan
        // $this->forge->dropColumn('pengeringan', 'id_bahan');
        // $this->forge->addColumn('pengeringan', [
        //     'durasi' => [
        //         'type' => 'INT',
        //         'constraint' => 3,
        //         'null' => true,
        //     ]
        // ]);
    }

    public function down()
    {
        // $this->forge->addColumn('pengeringan', [
        //     'id_bahan' => [
        //         'type' => 'INT',
        //         'constraint' => 11,
        //         'null' => true, // Atur null jika diperlukan
        //     ]
        // ]);
        // $this->forge->dropColumn('pengeringan', 'durasi');
    }
}
