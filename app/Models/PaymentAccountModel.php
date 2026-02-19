<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentAccountModel extends Model
{
    protected $table            = 'payment_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'account_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch',
        'upi_id',
        'instructions',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active payment account for display (first one)
     */
    public function getActive(): ?array
    {
        return $this->where('is_active', 1)->orderBy('id', 'DESC')->first();
    }

    /**
     * Get all active payment accounts
     */
    public function getAllActive(): array
    {
        $rows = $this->where('is_active', 1)->orderBy('id', 'DESC')->findAll();
        return $rows ?: [];
    }

    /**
     * Get all payment accounts (for admin list)
     */
    public function getAll(): array
    {
        $rows = $this->orderBy('id', 'DESC')->findAll();
        return $rows ?: [];
    }
}
