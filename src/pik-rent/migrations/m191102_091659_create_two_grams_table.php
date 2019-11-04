<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%two_grams}}`.
 */
class m191102_091659_create_two_grams_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('two_grams', [
            'tg_id' => $this->primaryKey()->comment('Identifier'),
            'tg_gram' => $this->string(2)->notNull()->comment('Part of word in a dictionary'),
        ]);

        $this->createIndex('searching_by_gram', 'two_grams', 'tg_gram', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('searching_by_gram', 'two_grams');
        $this->dropTable('two_grams');
    }
}
