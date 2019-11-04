<?php

use yii\db\Migration;

/**
 * Class m191102_092232_add_foreign_key_tgw
 */
class m191102_092232_add_foreign_key_tgw extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('getting_gram', 'two_gram_words', 'tgw_gram_id', 'two_grams', 'tg_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('getting_gram', 'two_gram_words');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191102_092232_add_foreign_key_tgw cannot be reverted.\n";

        return false;
    }
    */
}
