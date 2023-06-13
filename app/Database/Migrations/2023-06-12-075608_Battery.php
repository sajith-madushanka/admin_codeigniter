<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Battery extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'voltage' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => false,
            ],
            'device' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => null,
            ],
            'status' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'test_date' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('battery');
    }

    public function down()
    {
        $this->forge->dropTable('battery');
    }
}
