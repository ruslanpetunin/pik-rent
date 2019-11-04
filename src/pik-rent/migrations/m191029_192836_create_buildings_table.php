<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%buildings}}`.
 */
class m191029_192836_create_buildings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('buildings', [
            'b_id' => $this->primaryKey()->comment('Identifier'),
            'b_residential_complex_id' => $this->integer()->notNull()->comment('This one belongs residential complex by id'),
            'b_street_id' => $this->integer()->notNull()->comment('For searching address'),
            'b_house_number' => $this->integer()->notNull()->comment('House number on the street'),
            'b_building_number' => $this->integer()->notNull()->comment('Building number on the street'),
            'b_apartments_count' => $this->integer()->notNull()->comment('Count apartments of this building'),
            'b_floors_count' => $this->integer()->notNull()->comment('Count floors of this building'),
        ]);

        $this->addForeignKey(
            'street_building_search',
            'buildings', 'b_street_id',
            'streets', 's_id'
        );

        $this->createIndex('street_building_search_by_street', 'buildings', ['b_street_id', 'b_house_number', 'b_building_number'], true);

        $this->addForeignKey(
            'residential_complex_searching',
            'buildings', 'b_residential_complex_id',
            'residential_complexes', 'rc_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('street_building_search', 'buildings');
        $this->dropIndex('street_building_search_by_street', 'buildings');
        $this->dropForeignKey('residential_complex_searching', 'buildings');
        $this->dropTable('buildings');
    }
}
