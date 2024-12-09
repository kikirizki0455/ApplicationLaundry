<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveIdBarangFromTimbangan extends Migration
{
    public function up()
    {
        // Menghapus kolom id_barang
        // $this->forge->dropColumn('timbangan', 'id_barang');
    }

    public function down()
    {
        // Jika perlu, tambahkan kolom kembali saat rollback
        // $this->forge->addColumn('timbangan', [
        //         'id_barang' => [
        //             'type' => 'INT',
        //             'constraint' => 5,
        //             'unsigned' => true,
        //             'null' => false,
        //         ],
        //     ]);
    }
}
