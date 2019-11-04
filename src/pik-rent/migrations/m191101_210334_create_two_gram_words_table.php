<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%two_gram_words}}`.
 */
class m191101_210334_create_two_gram_words_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('two_gram_words', [
            'tgw_id' => $this->primaryKey()->comment('Identifier'),
            'tgw_gram_id' => $this->integer()->notNull()->comment('Part of word in a dictionary'),
            'tgw_word_id' => $this->integer()->notNull()->comment('Referrer to word'),
        ]);

        $this->createIndex('unique_key_twg', 'two_gram_words', ['tgw_gram_id', 'tgw_word_id'], true);
        $this->addForeignKey('referrer_to_word', 'two_gram_words', 'tgw_word_id', 'dictionary', 'd_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('unique_key_twg', 'two_gram_words');
        $this->dropForeignKey('referrer_to_word', 'two_gram_words');
        $this->dropTable('two_gram_words');
    }
}
