<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PneumaticPair extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'left_rfid' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ],
            'right_rfid' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ],
            'pair_status' => [
                'type' => 'VARCHAR',
                'constraint' => '1',
                'default' => 1,
            ],
            'final_status' => [
                'type' => 'VARCHAR',
                'constraint' => '1',
                'default' => 0,
            ],
            'device' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'final_test' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pneumatic_pair');
    }

    public function down()
    {
        $this->forge->dropTable('pneumatic_pair');
    }
}
