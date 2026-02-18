<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAadhaarOtps extends Migration
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
            'aadhaar_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
            ],
            'otp' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'verified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'api_response' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('aadhaar_number');

        $this->forge->createTable('aadhaar_otps');
    }

    public function down()
    {
        $this->forge->dropTable('aadhaar_otps');
    }
}

