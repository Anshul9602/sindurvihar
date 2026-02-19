<?php

namespace App\Models;

use CodeIgniter\Model;

class ChalanModel extends Model
{
    protected $table            = 'chalans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'allotment_id',
        'application_id',
        'user_id',
        'chalan_number',
        'amount',
        'status',
        'payment_id',
        'paid_at',
        'payment_account_id',
        'payment_proof',
        'verified_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique chalan number
     */
    public function generateChalanNumber(): string
    {
        $prefix = 'CHAL-' . date('Ymd') . '-';
        $last = $this->selectMax('id')->first();
        $seq = isset($last['id']) ? ((int) $last['id']) + 1 : 1;
        return $prefix . str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
    }
}
