<?php

namespace tests\unit\models;

use app\models\active\BannedSource;
use app\models\custom\PhoneConfirmation;
use Codeception\Test\Unit;
use Yii;

class PhoneConfirmationTest extends Unit
{
    protected $aTestSources = [
        'sPhone' => '79779367874',
        'sCookieIndicate' => 'fjaji43n42ooim42moomn',
        'sClientIp' => '192.168.1.1'
    ];

    public function testBanSource()
    {
        $oPhoneConfirmation = new PhoneConfirmation();

        expect($oPhoneConfirmation->load($this->aTestSources, ''))->true();
        expect($oPhoneConfirmation->validate())->true();
        expect(!!$oPhoneConfirmation->banSources())->true();
        expect($oPhoneConfirmation->howLongWillIBeBanned())->equals((int)Yii::getAlias('@bannedTimeDuringPhoneProof'));
        expect(count(BannedSource::find()->whereSourceIsBanned($oPhoneConfirmation->getSources())->all()))->equals(3);
    }

    public function testFreezeSources()
    {
        $oPhoneConfirmation = new PhoneConfirmation();

        expect($oPhoneConfirmation->load($this->aTestSources, ''))->true();
        expect($oPhoneConfirmation->validate())->true();
        expect(!!$oPhoneConfirmation->freezeSources())->true();
        expect($oPhoneConfirmation->howLongWillIBeBanned())->equals((int)Yii::getAlias('@freezingTimeDuringPhoneProof'));
        expect(count(BannedSource::find()->whereSourceIsBanned($oPhoneConfirmation->getSources())->all()))->equals(3);
    }

    public function testSpammingSources()
    {
        $aPhoneConfirmation = [
            new PhoneConfirmation(),
            new PhoneConfirmation()
        ];

        foreach ($aPhoneConfirmation as $oPhoneConfirmation) {
            expect($oPhoneConfirmation->load($this->aTestSources, ''))->true();
            expect($oPhoneConfirmation->validate())->true();
            expect(!!$oPhoneConfirmation->freezeSources())->true();
        }

        expect($aPhoneConfirmation[0]->isSpammingSources())->true();
    }

    public function testPhoneValidation()
    {
        $aInvalidPhones = ['89779367874', 'hfdsjghjhj43', '', '0', '7977936787', '797793678747'];

        for ($i = 0; $i < count($aInvalidPhones); $i++) {
            $oPhoneConfirmation = new PhoneConfirmation();
            $this->aTestSources['sPhone'] = $aInvalidPhones[$i];
            expect($oPhoneConfirmation->load($this->aTestSources, ''))->true();
            expect($oPhoneConfirmation->validate())->false();
        }
    }

}