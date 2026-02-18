<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKycFieldsToAadhaarOtps extends Migration
{
    public function up()
    {
        // Extra fields for TruthScreen / UIDAI compliant storage
        $fields = [
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'aadhaar_last4' => [
                'type'       => 'VARCHAR',
                'constraint' => 4,
                'null'       => true,
            ],
            'kyc_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => true,
            ],
            'kyc_dob' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'kyc_gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'kyc_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kyc_pincode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('aadhaar_otps', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('aadhaar_otps', [
            'request_id',
            'aadhaar_last4',
            'kyc_name',
            'kyc_dob',
            'kyc_gender',
            'kyc_address',
            'kyc_pincode',
        ]);
    }
}


