<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMesinCuci extends Migration
{
    public function up()
    {
        // $this->forge->addField([
        // 'id_mesin' => [
        //     'type' => 'INT',
        //     'auto_increment' => true,
        // ],
        // 'nama_mesin' => [
        //     'type' => 'VARCHAR',
        //     'constraint' => '255',
        // ],
        // 'kapasitas' => [
        //     'type' => 'DECIMAL',
        //     'constraint' => '5,2',
        // ],
        // 'status' => [
        //     'type' => 'ENUM',
        //     'constraint' => ['aktif', 'tidak_aktif'],
        // ],
        // 'created_at' => [
        //     'type' => 'DATETIME',
        //     'null' => true,
        // ],
        // 'updated_at' => [
        //     'type' => 'DATETIME',
        //     'null' => true,
        // ],
        // ]);


        // $this->forge->addPrimaryKey('id_mesin');
        // $this->forge->createTable('mesin_cuci'); // Pastikan nama tabel di sini konsisten
    }

    public function down()
    {
        // $this->forge->dropTable('mesin_cuci'); // Pastikan nama tabel di sini juga konsisten
    }
}
