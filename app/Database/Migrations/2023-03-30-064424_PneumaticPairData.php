<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PneumaticPairData extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'pair_id' => [
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
            ],
            'left_status' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'right_status' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'date_time' => [
                'type' => 'DATETIME'
            ],
            'remarks' => [
                'type' => 'VARCHAR',
                'constraint' => '1000',
                'default' => null,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pneumatic_pair_data');
    }

    public function down()
    {
        $this->forge->dropTable('pneumatic_pair_data');
    }
}
