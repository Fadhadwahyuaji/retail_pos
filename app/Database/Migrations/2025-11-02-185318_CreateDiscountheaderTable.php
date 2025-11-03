<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountheaderTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'NoTrans' => [
                'type'       => 'VARCHAR',
                'constraint' => '11',
            ],
            'TglTrans' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'Ketentuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => true,
            ],
            'TglAwal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'TglAkhir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jam_mulai' => [
                'type'    => 'TIME',
                'default' => '00:00:00',
                'comment' => 'Jam mulai promo berlaku',
            ],
            'jam_selesai' => [
                'type'    => 'TIME',
                'default' => '23:59:59',
                'comment' => 'Jam selesai promo berlaku',
            ],
            'hari_berlaku' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '1,2,3,4,5,6,7',
                'comment'    => 'Format: 1,2,3,4,5,6,7 (1=Senin, 7=Minggu)',
            ],
            'Minimum' => [
                'type'       => 'INT',
                'constraint' => 20,
                'default'    => '0',
            ],
            'Status' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
                'default'    => '0',
            ],
            'exclude_promo' => [
                'type'       => 'CHAR',
                'constraint' => '1',
                'null'       => true,
            ],
            'berlaku' => [
                'type'       => 'VARCHAR',
                'constraint' => '199',
                'null'       => true,
            ],
            'outlet_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'NULL = berlaku untuk semua outlet, jika diisi = hanya berlaku untuk outlet tertentu',
            ],
        ]);

        $this->forge->addKey('NoTrans', true);

        $this->forge->createTable('discountheader', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
            'ROW_FORMAT' => 'DYNAMIC',
        ]);

        // Tambahkan foreign key ke outlets setelah tabel dibuat
        $this->db->query('ALTER TABLE discountheader ADD CONSTRAINT fk_promo_outlets FOREIGN KEY (outlet_id) REFERENCES outlets(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key terlebih dahulu
        $this->db->query('ALTER TABLE discountheader DROP FOREIGN KEY IF EXISTS fk_promo_outlets');

        $this->forge->dropTable('discountheader', true);
    }
}
