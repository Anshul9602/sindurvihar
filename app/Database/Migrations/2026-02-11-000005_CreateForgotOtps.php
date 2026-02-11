<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateForgotOtps extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'otp' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');

        $this->forge->createTable('forgot_otps');
    }

    public function down()
    {
        $this->forge->dropTable('forgot_otps');
    }
}


