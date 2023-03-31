<?php

namespace App\Models;
use CodeIgniter\Model;

class PneumaticPair extends Model{

	protected $table = 'pneumatic_pair';
	protected $allowedFields = [
        'left_rfid',
        'right_rfid',
        'pair_status'
    ];

}