<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apartments}}`.
 */
class m191029_195243_create_apartments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('apartments', [
            'a_id' => $this->primaryKey()->comment('Identifier'),
            'a_building_id' => $this->integer()->notNull()->comment('This one belongs building by id'),
            'a_area' => $this->integer()->notNull()->comment('Area of this apartment in square meters'),
            'a_floor' => $this->integer()->notNull()->comment('Floor number of this apartment'),
            'a_rooms_count' => $this->integer()->notNull()->comment('Rooms count of this apartment'),
            'a_cost' => $this->integer()->notNull()->comment('Worth of this apartment'),
        ]);

        $this->addForeignKey(
            'building_searching',
            'apartments', 'a_building_id',
            'buildings', 'b_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('building_searching', 'apartments');
        $this->dropTable('apartments');
    }
}
