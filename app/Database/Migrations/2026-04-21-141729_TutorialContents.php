<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TutorialContents extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tutorial_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'sub_bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'isi_materi' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'is_visible' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('tutorial_id', 'master_tutorials', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tutorial_contents');
    }

    public function down()
    {
        $this->forge->dropTable('tutorial_contents',true);
    }
}
