<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengeringanTable    extends Migration
{
    public function up()
    {
        // $this->forge->addColumn('timbangan', [
        //     'status_pencucian' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['infeksi', 'non_infeksi'],
        //         'default' => 'non_infeksi', // Nilai default jika diperlukan
        //         'null' => false, // Set true jika Anda ingin kolom ini boleh bernilai null
        //     ],
        // ]);
        // table pegawai
        // $this->forge->addField([
        //     'id_pegawai' => [
        //         'type'           => 'INT',
        //         'constraint'     => 5,
        //         'unsigned'       => true,
        //         'auto_increment' => true,
        //     ],
        //     'nomor_pegawai' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => '100',
        //     ],
        //     'nama_pegawai' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => '100',
        //     ],
        //     'role_pegawai' => [
        //         'type'       => 'ENUM',
        //         'constraint' => ['admin', 'distribusi', 'pengelola'],
        //     ],
        //     'email' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => '100',
        //         'unique'     => true,
        //     ],
        //     'password' => [
        //         'type'       => 'VARCHAR',
        //         'constraint' => '255',
        //     ],
        // ]);
        // $this->forge->addKey('id_pegawai', true);
        // $this->forge->createTable('pegawai');

        // // Tabel Barang
        // $this->forge->addField([
        //     'id_barang' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'auto_increment' => true,
        //     ],
        //     'nama_barang' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['Laken', 'S.bantal', 'Selimut', 'Bedcover'],
        //     ],
        //     'stok' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //     ],
        // ]);
        // $this->forge->addKey('id_barang', true);
        // $this->forge->createTable('barang');

        // Tabel Bahan
        // $this->forge->addField([
        //     'id_bahan' => [
        //         'type' => 'SMALLINT',
        //         'constraint' => 6,
        //         'unsigned' => true,
        //         'auto_increment' => true,
        //     ],
        //     'nama_bahan' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['ditergen liquid', 'penetral', 'concenrated oxygen bleach', 'karbol sere', 'soklin'],
        //     ],
        //     'stok_bahan' => [
        //         'type' => 'SMALLINT',
        //         'constraint' => 6,
        //         'unsigned' => true,
        //     ],
        // ]);
        // $this->forge->addKey('id_bahan', true);
        // $this->forge->createTable('bahan');

        // Tabel Timbangan
        // $this->forge->addField([
        //     'id_timbangan' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'auto_increment' => true,
        //     ],
        //     'berat_barang' => [
        //         'type' => 'DECIMAL',
        //         'constraint' => '8,2',
        //     ],
        //     'nama_barang' => [
        //         'type' => 'VARCHAR',
        //         'constraint' => '100',
        //     ],
        //     'status' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['pending', 'in_progress', 'completed'],
        //     ],
        // ]);
        // $this->forge->addKey('id_timbangan', true);
        // $this->forge->createTable('timbangan');

        // // Tabel Pencucian
        // $this->forge->addField([
        //     'id_cuci' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'auto_increment' => true,
        //     ],
        //     'id_timbangan' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //     ],
        //     'waktu_pencucian' => [
        //         'type' => 'INT',
        //         'constraint' => 3,
        //     ],
        //     'id_bahan' => [
        //         'type' => 'SMALLINT',
        //         'constraint' => 6,
        //         'unsigned' => true,
        //     ],
        //     'jumlah_bahan' => [
        //         'type' => 'INT',
        //         'constraint' => 3,
        //     ],
        //     'status' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['pending', 'in_progress', 'completed'],
        //     ],
        //     'status_pencucian' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['infeksi', 'non_infeksi'],
        //     ],
        //     'tanggal_mulai' => [
        //         'type' => 'TIMESTAMP',
        //     ],
        //     'tanggal_selesai' => [
        //         'type' => 'TIMESTAMP',
        //         'null' => true,
        //     ],
        // ]);
        // $this->forge->addKey('id_cuci', true);
        // $this->forge->addForeignKey('id_timbangan', 'timbangan', 'id_timbangan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_bahan', 'bahan', 'id_bahan', 'CASCADE', 'CASCADE');
        // $this->forge->createTable('pencucian');
        // $this->forge->addColumn('pencucian', [
        //     'berat_barang' => [
        //         'type' => 'DECIMAL',
        //         'constraint' => '8,2',
        //     ],
        // ]);
        // $this->forge->addColumn('pencucian', [
        //     'nama_barang' => [
        //         'type' => 'VARCHAR',
        //         'constraint' => '100',
        //     ],
        // ]);
        // $this->forge->addColumn('pencucian', [
        //     'waktu_estimasi' => [
        //         'type' => 'INT',
        //         'constraint' => 3,
        //         'null' => true,
        //     ],
        // ]);

        // 'id' => [
        //     'type' => 'INT',
        //     'constraint' => 11,
        //     'unsigned' => true,
        //     'auto_increment' => true,
        // ],
        // 'nama_barang' => [
        //     'type' => 'VARCHAR',
        //     'constraint' => 255,
        // ],
        // 'berat_barang' => [
        //     'type' => 'INT',
        //     'constraint' => 11,
        // ],
        // 'tanggal_rusak' => [
        //     'type' => 'DATE',
        // ],
        // 'keterangan' => [
        //     'type' => 'TEXT',
        //     'null' => true,
        // ],
        // ]);
        // $this->forge->addKey('', true);
        // $this->forge->createTable('rusak');

        //tabel pengeringan
        // $this->forge->addField([
        //     'id_pengeringan' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //         'auto_increment' => true,
        //     ],
        //     'id_pencucian' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //     ],
        //     'id_timbangan' => [
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => true,
        //     ],
        //     'id_bahan' => [
        //         'type' => 'SMALLINT',
        //         'constraint' => 6,
        //         'unsigned' => true,
        //     ],
        //     'tanggal_mulai' => [
        //         'type' => 'DATETIME',
        //     ],
        //     'tanggal_selesai' => [
        //         'type' => 'DATETIME',
        //         'null' => true,
        //     ],
        //     'status' => [
        //         'type' => 'ENUM',
        //         'constraint' => ['pending','in_progress', 'completed'],
        //         'default' => 'in_progress',
        //     ],
        // ]);
        // $this->forge->addKey('id_pengeringan', true);
        // $this->forge->addForeignKey('id_pencucian', 'pencucian', 'id_cuci', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_timbangan', 'timbangan', 'id_timbangan', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('id_bahan', 'bahan', 'id_bahan', 'CASCADE', 'CASCADE');
        // $this->forge->createTable('pengeringan');
    }

    public function down()
    {
        // $this->forge->dropColumn('timbangan', 'status_pencucian');
        // $this->forge->dropColumn('pencucian', ['status', 'status_pencucian']);
        // $this->forge->dropTable('timbangan');
        // $this->forge->dropTable('bahan');
        // $this->forge->dropTable('barang');
        // $this->forge->dropTable('pegawai');
        // $this->forge->dropTable('rusak');
        // $this->forge->dropColumn('timbangan', 'status_pencucian');
        $this->forge->dropTable('pengeringan');
    }
}
