<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'language',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'category');
    }
}

