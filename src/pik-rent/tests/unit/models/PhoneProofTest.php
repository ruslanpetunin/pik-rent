<?php

namespace tests\unit\models;

use app\models\active\PhoneProof;
use Codeception\Test\Unit;
use Yii;

class PhoneProofTest extends Unit
{
    protected $sValidPhone = '79779367874';

    public function testCreatingConfirmationCodeForPhone()
    {
        $oPhoneProof = $this->createProofPhone();
        $iTime = date('Y-m-d H:i:s', time() + Yii::getAlias('@phoneConfirmationCodeLifeTime'));

        expect($oPhoneProof->pp_phone)->equals($this->sValidPhone);
        expect($oPhoneProof->pp_code >= 100000 && $oPhoneProof->pp_code <= 999999)->true();
        expect($oPhoneProof->pp_used)->equals(0);
        expect($oPhoneProof->pp_expiration_date)->equals($iTime);
    }

    public function testSavingUsedProofPhone()
    {
        $oPhoneProof = $this->createProofPhone();
        $oPhoneProofForUpdated = PhoneProof::find()->wherePhoneAndCode($oPhoneProof->pp_phone, $oPhoneProof->pp_code)->one();
        $oPhoneProofForUpdated->scenario = PhoneProof::SCENARIO_SET_USED;
        $iTime = date('Y-m-d H:i:s', time());
        $oPhoneProofForUpdated->save();

        expect($oPhoneProofForUpdated->pp_phone)->equals($this->sValidPhone);
        expect($oPhoneProofForUpdated->pp_code >= 100000 && $oPhoneProof->pp_code <= 999999)->true();
        expect($oPhoneProofForUpdated->pp_used)->equals(1);
        expect($oPhoneProofForUpdated->pp_expiration_date)->equals($iTime);
    }

    public function createProofPhone()
    {
        $oPhoneProof = new PhoneProof(['scenario' => PhoneProof::SCENARIO_CHECKING_PHONE]);

        expect($oPhoneProof->load(['pp_phone' => $this->sValidPhone], ''))->true();
        expect($oPhoneProof->validate())->true();
        expect($oPhoneProof->save())->true();

        return $oPhoneProof;
    }
}
