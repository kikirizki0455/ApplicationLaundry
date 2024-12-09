<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddToTimbanganIDBarangAsForeign extends Migration
{
    public function up()
    {
        // Tambahkan kolom id_barang jika belum ada
        $this->forge->addColumn('timbangan', [
            'id_barang' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => false,
            ],
        ]);

        // Menambahkan foreign key constraint dengan nama yang ditetapkan
        $this->forge->addForeignKey('id_barang', 'barang', 'id_barang', 'CASCADE', 'CASCADE', 'fk_timbangan_barang');
    }

    public function down()
    {
        // Menghapus foreign key constraint dengan nama yang ditetapkan
        $this->forge->dropForeignKey('timbangan', 'fk_timbangan_barang');

        // Menghapus kolom id_barang jika perlu
        $this->forge->dropColumn('timbangan', 'id_barang');
    }
}
