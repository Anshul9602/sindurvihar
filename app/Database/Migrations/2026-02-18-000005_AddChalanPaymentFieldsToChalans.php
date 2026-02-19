<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddChalanPaymentFieldsToChalans extends Migration
{
    public function up()
    {
        $fields = [
            'payment_account_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'paid_at',
            ],
            'payment_proof' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'payment_account_id',
            ],
        ];
        $this->forge->addColumn('chalans', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('chalans', ['payment_account_id', 'payment_proof']);
    }
}
