<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dictionary}}`.
 */
class m191101_205746_create_dictionary_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dictionary', [
            'd_id' => $this->primaryKey()->comment('Identifier'),
            'd_word' => $this->string()->notNull()->comment('One word'),
            'd_hash' => $this->string(32)->notNull()->comment('md5 hash of word'),
        ]);

        $this->createIndex('searching_by_hash', 'dictionary', 'd_hash', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('searching_by_hash', 'dictionary');
        $this->dropTable('dictionary');
    }
}
