<?php

namespace App\Models;
use CodeIgniter\Model;


class Device extends Model{

	protected $table = 'device';
    protected $primaryKey = 'id';
	protected $allowedFields = [
        'name',
        'file',
        'status',
        'token'
    ];

}