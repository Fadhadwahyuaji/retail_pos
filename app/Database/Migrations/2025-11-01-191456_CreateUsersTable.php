<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'role_id'    => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true], // Foreign Key ke tabel roles
            'nama'       => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'      => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => '255'],
            'outlet_id'  => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'default' => 0], // Untuk Manajer/Kasir
            'is_active'  => ['type' => 'BOOLEAN', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE'); // Membuat Foreign Key
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
