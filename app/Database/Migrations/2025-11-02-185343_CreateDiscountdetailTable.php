<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiscountdetailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'NoTrans' => [
                'type'       => 'VARCHAR',
                'constraint' => '11',
            ],
            'PCode' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'Jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
                'null'       => true,
                'comment'    => 'P = persen, R = rupiah',
            ],
            'Nilai' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey(['NoTrans', 'PCode'], true);

        $this->forge->createTable('discountdetail', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
            'ROW_FORMAT' => 'DYNAMIC',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('discountdetail', true);
    }
}
