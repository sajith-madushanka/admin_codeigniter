<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Device extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ],
            'status' => [
                'type' => 'BOOLEAN',
                'default' => 0,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('device');
    }

    public function down()
    {
        $this->forge->dropTable('device');
    }
}
