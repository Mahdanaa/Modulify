<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterTutorial extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'kode_mk'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'url_presentation' => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'url_finished'     => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'creator_email'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('master_tutorials');
    }

    public function down()
    {
        $this->forge->dropTable('master_tutorials',true);
    }
}
