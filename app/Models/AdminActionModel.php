<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminActionModel extends Model
{
    protected $table            = 'admin_actions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'application_id',
        'admin_id',
        'action_type',
        'reason',
        'notes',
        'confirmed',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'application_id' => 'required|integer',
        'action_type'    => 'required|in_list[verified,rejected]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

