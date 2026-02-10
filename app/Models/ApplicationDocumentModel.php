<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationDocumentModel extends Model
{
    protected $table            = 'application_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'application_id',
        'has_identity_proof',
        'has_income_proof',
        'has_residence_proof',
        'identity_files',
        'income_files',
        'residence_files',
        'annexure_files',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}


