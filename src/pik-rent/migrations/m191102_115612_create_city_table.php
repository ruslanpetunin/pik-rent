<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m191102_115612_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cities', [
            'c_id' => $this->primaryKey()->comment('Identifier'),
            'c_name' => $this->string()->notNull()->comment('Name of city'),
            'c_hash_name' => $this->string(32)->notNull()->comment('Key for searching'),
        ]);

        $this->createIndex('for_search_city', 'cities', 'c_hash_name', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('for_search_city', 'cities');
        $this->dropTable('cities');
    }
}
