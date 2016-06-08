<?php

use yii\db\Migration;
use yii\db\Schema;

class m160602_043809_add_todo extends Migration
{
    public function up()
    {
        $this->createTable('todos', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
            'status' => Schema::TYPE_STRING
        ]);
    }

    public function down()
    {
        $this->dropTable('todos');
    }
}
