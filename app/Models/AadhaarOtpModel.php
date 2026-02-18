<?php

namespace App\Models;

use CodeIgniter\Model;

class AadhaarOtpModel extends Model
{
    protected $table            = 'aadhaar_otps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'aadhaar_number',
        'otp',              // used only in demo mode
        'verified',
        'api_response',
        'request_id',
        'aadhaar_last4',
        'kyc_name',
        'kyc_dob',
        'kyc_gender',
        'kyc_address',
        'kyc_pincode',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

