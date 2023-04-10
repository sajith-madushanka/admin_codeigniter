<?php

namespace App\Models;
use CodeIgniter\Model;

class PneumaticPairData extends Model{

	protected $table = 'pneumatic_pair_data';
	protected $allowedFields = [
        'pair_id',
        'lt',
        'lm',
        'lb',
        'rt',
        'rm',
        'rb',
        'left_status',
        'right_status',
        'date_time'
    ];

}