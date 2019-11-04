<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hash_signature_words}}`.
 */
class m191101_211644_create_hash_signature_words_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hash_signature_words', [
            'hsw_id' => $this->primaryKey()->comment('Identifier'),
            'hsw_hash' => $this->string(13)->notNull()->comment('Hash signature of word'),
            'hsw_word_id' => $this->integer()->notNull()->comment('Referrer to word'),
        ]);

        $this->createIndex('searching_by_signature', 'hash_signature_words', 'hsw_hash');
        $this->addForeignKey('referrer_to_word_hsw', 'hash_signature_words', 'hsw_word_id', 'dictionary', 'd_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('searching_by_signature', 'hash_signature_words');
        $this->dropForeignKey('referrer_to_word_hsw', 'hash_signature_words');
        $this->dropTable('hash_signature_words');
    }
}
