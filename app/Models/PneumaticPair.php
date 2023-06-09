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
        'device',
        'updated_at',
        'final_test',
        'pinned'
    ];

    public function pair_data()
    {
        return $this->hasMany('pair_data', 'App\Models\PneumaticPairData','pair_id');
        // $this->hasMany('propertyName', 'model', 'foreign_key', 'local_key');
    }

}