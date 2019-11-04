<?php

namespace tests\unit\models;

use app\models\active\BannedSource;
use Codeception\Test\Unit;
use Yii;

class BannedSourceTest extends Unit
{
    protected $sSourceToBan = '192.160.1.0';

    public function testBanSource()
    {
        $oBannedSource = new BannedSource(['scenario' => BannedSource::SCENARIO_BAN_SOURCES]);
        $iTime = date('Y-m-d H:i:s', time() + Yii::getAlias('@bannedTimeDuringPhoneProof'));

        expect($oBannedSource->load(['bs_source' => $this->sSourceToBan, 'bs_batch' => 1], ''))->true();
        expect($oBannedSource->validate())->true();
        expect($oBannedSource->save())->true();
        expect($oBannedSource->bs_source)->equals($this->sSourceToBan);
        expect($oBannedSource->bs_expiration_date)->equals($iTime);
        expect($oBannedSource->bs_type)->equals(BannedSource::FREEZED_SOURCE_TYPE);
        expect($oBannedSource->bs_batch)->equals(1);
    }

    public function testFreezingSource()
    {
        $oBannedSource = new BannedSource(['scenario' => BannedSource::SCENARIO_FREEZE_SOURCES]);
        $iTime = date('Y-m-d H:i:s', time() + Yii::getAlias('@freezingTimeDuringPhoneProof'));

        expect($oBannedSource->load(['bs_source' => $this->sSourceToBan, 'bs_batch' => 1], ''))->true();
        expect($oBannedSource->validate())->true();
        expect($oBannedSource->save())->true();
        expect($oBannedSource->bs_source)->equals($this->sSourceToBan);
        expect($oBannedSource->bs_expiration_date)->equals($iTime);
        expect($oBannedSource->bs_type)->equals(BannedSource::FREEZED_SOURCE_TYPE);
        expect($oBannedSource->bs_batch)->equals(1);
    }
}
