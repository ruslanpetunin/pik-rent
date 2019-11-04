<?php

use app\models\active\Apartment;
use app\models\active\Building;
use yii\db\Migration;

/**
 * Class m191102_145143_fill_appartments
 */
class m191102_145143_fill_appartments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $aBuildings = Building::find()->all();

        for ($i = 0; $i < count($aBuildings); $i++) {
            $iCountApartments = rand(0, 10);

            for ($j = 0; $j < $iCountApartments; $j++) {
                $oApartment = new Apartment();
                $oApartment->a_area = rand(20, 60);
                $oApartment->a_rooms_count = rand(1, 6);
                $oApartment->a_cost = rand(2000000, 9000000);
                $oApartment->a_floor = rand(1, $aBuildings[$i]->b_floors_count);
                $oApartment->a_building_id = $aBuildings[$i]->b_id;

                $oApartment->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('apartments', 'a_id > 0');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191102_145143_fill_appartments cannot be reverted.\n";

        return false;
    }
    */
}
