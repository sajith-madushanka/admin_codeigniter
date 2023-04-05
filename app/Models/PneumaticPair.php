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
        'updated_at'
    ];

    public function getFilteredData($search = '', $limit, $offset)
    {
        $builder = $this->table('pneumatic_pair');

        if (!empty($search)) {
            $builder->like('id', $search);
        }

        return $builder->limit($limit, $offset)->get()->getResult();
    }

    public function pair_records()
    {
       // return $this->hasMany('address', 'App\Models\Address');
        return $this->hasMany('App\Models\PneumaticPairData','pair_id','id');
        // $this->hasOne('propertyName', 'model', 'foreign_key', 'local_key');
    }

}