<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnNoInvoice extends Migration
{
    public function up()
    {
        // $this->forge->addColumn('timbangan', [
        //     'no_invoice' => [
        //         'type' => 'VARCHAR',
        //         'constraint' => 50,
        //         'null' => false,
        //         'after' => 'berat_barang'
        //     ]
        // ]);
    }

    public function down()
    {
        // $this->forge->dropColumn('timbangan', 'no_invoice');
    }
}
