<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropIDBahanTablePenyetrikaan extends Migration
{
    public function up()
    {
        // $this->forge->addField([
        //     'id_penyetrikaan' => [
        //         'type'           => 'INT',
        //         'constraint'     => 5,
        //         'unsigned'       => true,
        //         'auto_increment' => true,
        //     ],
        //     'id_pengeringan' => [
        //         'type'       => 'INT',
        //         'constraint' => 5,
        //         'unsigned'   => true,
        //     ],
        //     'id_timbangan' => [
        //         'type'       => 'INT',
        //         'constraint' => 5,
        //         'unsigned'   => true,
        //     ],
        //     'id_bahan' => [
        //         'type'       => 'SMALLINT',
        //         'constraint' => 6,
        //         'unsigned'   => true,
        //     ],
        //     'tanggal_mulai' => [
        //         'type'       => 'DATETIME',
        //         'null'       => false,
        //     ],
        //     'tanggal_selesai' => [
        //         'type'       => 'DATETIME',
        //         'null'       => true,
        //     ],
        //     'status' => [
        //         'type'       => 'ENUM',
        //         'constraint' => ['pending', 'in_progress', 'completed'],
        //         'default'    => 'in_progress',
        //     ],
        //     'created_at' => [
        //         'type' => 'DATETIME',
        //         'null' => true,
        //         'default' => null,
        //     ],
        //     'updated_at' => [
        //         'type' => 'DATETIME',
        //         'null' => true,
        //         'default' => null,
        //     ],
        // ]);

        // $this->forge->addPrimaryKey('id_penyetrikaan');
        // $this->forge->addForeignKey('id_pengeringan', 'pengeringan', 'id_pengeringan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_timbangan', 'timbangan', 'id_timbangan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_bahan', 'bahan', 'id_bahan', 'CASCADE', 'CASCADE');

        // $this->forge->createTable('penyetrikaan');
        // Hapus foreign key dulu

        // $this->forge->dropColumn('penyetrikaan', 'id_bahan');
        // $this->forge->dropForeignKey('penyetrikaan', 'penyetrikaan_id_bahan_foreign');

        // // Jika ingin hapus index juga, bisa tambahkan perintah ini
        // $this->forge->dropKey('penyetrikaan', 'penyetrikaan_id_bahan_foreign');
    }

    public function down()
    {
        // $this->forge->dropTable('penyetrikaan');
    }
}
