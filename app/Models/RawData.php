<?php

namespace App\Models;
use CodeIgniter\Model;

class RawData extends Model{

	protected $table = 'raw_data';
	protected $allowedFields = [
        'pair_data_id',
        'lt',
        'lm',
        'lb',
        'rt',
        'rm',
        'rb'
    ];

}