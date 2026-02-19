<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoundToAllotments extends Migration
{
    public function up()
    {
        $fields = [
            'lottery_round' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'status',
            ],
            'lottery_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'lottery_round',
            ],
        ];
        $this->forge->addColumn('allotments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('allotments', ['lottery_round', 'lottery_category']);
    }
}
