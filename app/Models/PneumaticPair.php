<?php

namespace App\Models;
use CodeIgniter\Model;

class PneumaticPair extends Model{

	protected $table = 'pneumatic_pair';
    protected $primaryKey = 'id';
	protected $allowedFields = [
        'left_rfid',
        'right_rfid',
        'pair_status',
        'final_status',
        'updated_at'
    ];

}