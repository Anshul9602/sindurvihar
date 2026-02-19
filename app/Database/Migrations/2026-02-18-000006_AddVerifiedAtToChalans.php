<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerifiedAtToChalans extends Migration
{
    public function up()
    {
        $this->forge->addColumn('chalans', [
            'verified_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'payment_proof',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('chalans', 'verified_at');
    }
}
