<?php

use yii\db\Migration;

/**
 * Class m191102_120732_alter_streets_table
 */
class m191102_120732_alter_streets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('streets', 's_district_id', $this->integer()->notNull()->comment('Referrer to district'));
        $this->addColumn('streets', 's_hash_name', $this->string(32)->notNull()->comment('For searching'));
        $this->createIndex('for_searching_street', 'streets', ['s_hash_name', 's_district_id'], true);
        $this->addForeignKey('for_matching_district', 'streets', 's_district_id', 'districts', 'd_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('for_searching_street', 'streets');
        $this->dropForeignKey('for_matching_district', 'streets');
        $this->dropColumn('streets', 's_hash_name');
        $this->dropColumn('streets', 's_district_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191102_120732_alter_streets_table cannot be reverted.\n";

        return false;
    }
    */
}
