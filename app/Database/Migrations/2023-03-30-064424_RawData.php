<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RawData extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'pair_data_id' => [
                'type' => 'INT'
            ],
            'lt' => [
                'type' => 'JSON',
            ],
            'lm' => [
                'type' => 'JSON',
            ],
            'lb' => [
                'type' => 'JSON',
            ],
            'rt' => [
                'type' => 'JSON',
            ],
            'rm' => [
                'type' => 'JSON',
            ],
            'rb' => [
                'type' => 'JSON',
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('raw_data');
    }

    public function down()
    {
        $this->forge->dropTable('raw_data');
    }
}
