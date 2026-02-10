<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationModel extends Model
{
    protected $table            = 'applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'scheme_id', 'status', 'full_name', 'aadhaar', 
        'address', 'city', 'state', 'income', 'income_category'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get applications with user details
     */
    public function getApplicationsWithUsers()
    {
        return $this->select('applications.*, users.name as user_name, users.mobile, users.email')
                    ->join('users', 'users.id = applications.user_id', 'left')
                    ->orderBy('applications.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get application by ID with user details
     */
    public function getApplicationWithUser($id)
    {
        return $this->select('applications.*, users.name as user_name, users.mobile, users.email')
                    ->join('users', 'users.id = applications.user_id', 'left')
                    ->where('applications.id', $id)
                    ->first();
    }
}

