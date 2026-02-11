<?php

namespace App\Models;

use CodeIgniter\Model;

class PlotModel extends Model
{
    protected $table            = 'plots';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'plot_name',
        'plot_number',
        'category',
        'dimensions',
        'area',
        'location',
        'plot_image',
        'quantity',
        'available_quantity',
        'price',
        'status',
        'description',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'plot_name' => 'required|max_length[255]',
        'category'   => 'required|max_length[50]',
        'location'  => 'required|max_length[255]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

