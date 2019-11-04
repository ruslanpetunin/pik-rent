<?php

use app\components\filters_system\traits\HashHelper;
use app\models\active\Building;
use app\models\active\Dictionary;
use app\models\active\ResidentialComplex;
use app\models\active\Street;
use yii\db\Migration;

/**
 * Class m191102_135409_fill_residential_complexes
 */
class m191102_135409_fill_residential_complexes extends Migration
{
    use HashHelper;

    protected $aResidentialComplexes = [
        'ЖК Шуваловский',
        'ЖК Воробьевы горы',
        'ЖК ОКО',
        'ЖК Доминион',
        'ЖК Мичуринский',
        'ЖК Эдельвейс',
        'ЖК Скай Форт',
        'ЖК Дом на Мосфильмовской',
        'ЖК Золотые Ключи',
        'ЖК Золотые ключи 2',
        'ЖК Триумф Палас',
        'ЖК Английский Квартал',
        'ЖК Баркли Парк',
        'ЖК Камелот',
        'ЖК Дом на Беговой',
        'ЖК Легенда Цветного',
        'ЖК Имперский Дом',
        'ЖК Город Столиц',
        'ЖК Трианон',
        'ЖК Четыре Ветра',
        'ЖК Крылатские Огни',
        'ЖК Альбатрос',
        'ЖК Новая Остоженка',
        'ЖК Город яхт',
        'ЖК Студенческая 20',
        'ЖК Шмитовский',
        'ЖК Аэробус',
        'ЖК Кунцево',
        'ЖК Дом на Смоленской набережной',
        'ЖК Опера Хаус',
        'ЖК Алиса',
        'ЖК Кутузовская Ривьера',
        'ЖК Итальянский квартал',
        'ЖК Маршал',
        'ЖК Гранд Парк',
        'ЖК Квартал на Ленинском',
        'ЖК Меркурий Сити',
        'ЖК Дом на Давыдковской',
        'ЖК Коперник',
        'ЖК Алые Паруса',
        'ЖК Башня Санкт-Петербург',
        'ЖК Литератор',
        'ЖК Северный парк',
        'ЖК Серебряный фонтан',
        'ЖК Квартал ONLY',
        'ЖК Well House на Ленинском',
        'ЖК Московская, 21',
        'ЖК Studio 8',
        'ЖК Петровский замок',
        'ЖК Дом в Шебашевском переулке',
        'ЖК Флотская 14 ',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $aStreets = Street::find()->all();

        for ($i = 0; $i < count($aStreets); $i++) {
//            for ($j = 0; $j < 5; $j++) {
                $iIndexRC = rand(0, 40);
                $iCountBuildings = rand(1, 3);
                $sRCName = $this->aResidentialComplexes[$iIndexRC];
                $oResidentialComplex = ResidentialComplex::findOne(['rc_hash_name' => $this->getHash($sRCName)]);

                if (!$oResidentialComplex) {
                    $oResidentialComplex = new ResidentialComplex();
                    $oResidentialComplex->rc_name = $sRCName;
                    $oResidentialComplex->save();
                    $this->rememberAllWordsFromString($oResidentialComplex->rc_name);
                }



                for ($k = 0; $k < $iCountBuildings; $k++) {
                    $oBuilding = new Building();
                    $oBuilding->b_residential_complex_id = $oResidentialComplex->rc_id;
                    $oBuilding->b_street_id = $aStreets[$i]->s_id;
                    $oBuilding->b_apartments_count = rand(20, 150);
                    $oBuilding->b_building_number = Building::find()->where(['b_street_id' => $aStreets[$i]->s_id])->max('b_house_number') + 1;
                    $oBuilding->b_house_number = $oBuilding->b_building_number * 10;
                    $oBuilding->b_floors_count = rand(5, 30);

                    $oBuilding->save();
                }
//            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('buildings', 'b_id > 0');
        $this->delete('residential_complexes', 'rc_id > 0');
    }

    public function rememberAllWordsFromString($sText)
    {
        return preg_replace_callback(
            '/[а-яА-ЯA-Za-z]*/iu',
            function($sWord) {
                if ($sWord[0]) {
                    $sHash = $this->getHash($sWord[0]);

                    if (!Dictionary::findOne(['d_hash' => $sHash])) {
                        Yii::$app->spellChecker->rememberWord($sWord[0]);
                    }
                }

                return $sWord[0];
            },
            $sText
        );
    }
}
