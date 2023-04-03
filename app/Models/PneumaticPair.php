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

    public function get_filtered_data($filter_name,$perpage,$paginate)
    {
        $data = $this->db->get('pneumatic_pair',$perpage,$paginate)->result_array();
       // $builder->like('left_rfid', $filter_name);
        return $data;
    }

}