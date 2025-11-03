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
            'outlet_id'  => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
                'default' => null
            ], // Untuk Manajer/Kasir
            'is_active'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // Add foreign keys after table creation
        $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_users_outlets FOREIGN KEY (outlet_id) REFERENCES outlets(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
