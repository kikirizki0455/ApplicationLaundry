<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTimbanganTable extends Migration
{
    public function up()
    {
        // Tambahkan kolom id_barang dan id_pegawai ke tabel timbangan
        // $this->forge->addColumn('timbangan', [
        //     'id_barang' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'null' => false,
        //     ],
        //     'id_pegawai' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'null' => false,
        //     ],
        // ]);

        // Menambahkan foreign key untuk id_barang dan id_pegawai
        // $this->forge->addForeignKey('id_barang', 'barang', 'id_barang', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_pegawai', 'pegawai', 'id_pegawai', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // Hapus foreign key dan kolom yang terkait
        // $this->forge->dropForeignKey('timbangan', 'timbangan_id_barang_foreign');
        // $this->forge->dropForeignKey('timbangan', 'timbangan_id_pegawai_foreign');
        // $this->forge->dropColumn('timbangan', 'id_barang');
    }
}
