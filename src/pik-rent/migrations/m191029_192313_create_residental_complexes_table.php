<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%residental_complexes}}`.
 */
class m191029_192313_create_residental_complexes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('residential_complexes', [
            'rc_id' => $this->primaryKey()->comment('Identifier'),
            'rc_name' => $this->string()->notNull()->comment('Name of residential complex'),
            'rc_hash_name' => $this->string(32)->notNull()->comment('For searching'),
        ]);

        $this->createIndex('for_searching_rc', 'residential_complexes', 'rc_hash_name', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('for_searching_rc', 'residential_complexes');
        $this->dropTable('residential_complexes');
    }
}
