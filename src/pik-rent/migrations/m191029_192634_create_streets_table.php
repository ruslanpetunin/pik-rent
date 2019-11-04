<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%streets}}`.
 */
class m191029_192634_create_streets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('streets', [
            's_id' => $this->primaryKey()->comment('Identifier'),
            's_name' => $this->string()->notNull()->comment('Name of street'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('streets');
    }
}
