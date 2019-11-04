<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%districts}}`.
 */
class m191102_120222_create_districts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('districts', [
            'd_id' => $this->primaryKey()->comment('Identifier'),
            'd_name' => $this->string()->notNull()->comment('Name of district'),
            'd_city_id' => $this->integer()->notNull()->comment('Referrer to the city'),
            'd_hash_name' => $this->string(32)->notNull()->comment('For searching'),
        ]);

        $this->createIndex('for_search_district', 'districts', ['d_hash_name', 'd_city_id'], true);
        $this->addForeignKey('for_match_city', 'districts', 'd_city_id', 'cities', 'c_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('for_search_district', 'districts');
        $this->dropForeignKey('for_match_city', 'districts');
        $this->dropTable('districts');
    }
}
