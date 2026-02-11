<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminActions extends Migration
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
            'application_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'action_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'verified or rejected',
            ],
            'reason' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Reason for rejection (if rejected)',
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Additional notes or comments',
            ],
            'confirmed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => 'Whether admin confirmed the action',
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
        $this->forge->addKey('application_id');
        $this->forge->addKey('admin_id');
        $this->forge->createTable('admin_actions', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin_actions', true);
    }
}

