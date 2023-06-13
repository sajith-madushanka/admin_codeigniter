<?php

namespace App\Models;
use CodeIgniter\Model;


class Battery extends Model{

	protected $table = 'battery';
    protected $primaryKey = 'id';
	protected $allowedFields = [
        'voltage',
        'device',
        'status',
        'test_date'
    ];

}