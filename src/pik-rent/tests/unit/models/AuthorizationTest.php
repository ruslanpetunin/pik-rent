<?php

namespace tests\unit\models;

use app\components\user\Authentication;
use app\models\active\PhoneProof;
use app\models\custom\Authorization;
use Codeception\Test\Unit;

class AuthorizationTest extends Unit
{
    public $aTestValidData = [
        'sMailUser' => 'petunin.ruslan@yandex.ru',
        'sName' => 'Руслан',
        'sSurname' => 'Петунин',
        'sPhone' => '79779367874',
        'iCodeConfirmationPhone' => '123123',
        'sPassword' => '32njb3h2Bbh3#',
    ];

    public $aTestInvalidData = [
        'sMailUser' => 'аввыа.ruslan@yandex.ru',
        'sName' => 'Руслан Бык',
        'sSurname' => 'Петунин 32',
        'sPhone' => '797793678743',
        'iCodeConfirmationPhone' => 'Fds123123',
        'sPassword' => '32njb3h2B bh3#',
    ];

    public function testScenarioSingUpData()
    {
        $oAuthorization = new Authorization(['scenario' => Authorization::SCENARIO_SIGN_UP]);

        expect($oAuthorization->load($this->aTestValidData, ''))->true();
        expect($oAuthorization->validate())->true();
    }

    public function testScenarioSingInData()
    {
        $oAuthorization = new Authorization(['scenario' => Authorization::SCENARIO_SIGN_IN]);
        unset($this->aTestValidData['sName']);
        unset($this->aTestValidData['sSurname']);
        unset($this->aTestValidData['sPhone']);
        unset($this->aTestValidData['iCodeConfirmationPhone']);

        expect($oAuthorization->load($this->aTestValidData, ''))->true();
        expect($oAuthorization->validate())->true();
    }

    public function testInvalidData()
    {
        $oAuthorization = new Authorization(['scenario' => Authorization::SCENARIO_SIGN_UP]);

        expect($oAuthorization->load($this->aTestInvalidData, ''))->true();
        expect($oAuthorization->validate())->false();
        expect($oAuthorization->errors['sMailUser'][0])->equals("mail is not a valid email address.");
        expect($oAuthorization->errors['sName'][0])->equals("name is invalid.");
        expect($oAuthorization->errors['sSurname'][0])->equals("surname is invalid.");
        expect($oAuthorization->errors['sPhone'][0])->equals("phone is invalid.");
        expect($oAuthorization->errors['iCodeConfirmationPhone'][0])->equals("code must be a number.");
        expect($oAuthorization->errors['sPassword'][0])->equals("password is invalid.");
    }

    public function testDefinePhoneProof()
    {
        $oPhoneProof = new PhoneProof(['scenario' => PhoneProof::SCENARIO_CHECKING_PHONE]);
        $oAuthorization = new Authorization(['scenario' => Authorization::SCENARIO_SIGN_UP]);

        $oPhoneProof->load(['pp_phone' => $this->aTestValidData['sPhone']], '');
        $oPhoneProof->save();

        $this->aTestValidData['iCodeConfirmationPhone'] = $oPhoneProof->pp_code;
        $oAuthorization->load($this->aTestValidData, '');

        expect($oAuthorization->definePhoneProof()->toArray())->equals($oPhoneProof->toArray());
    }

    public function testDefineUser()
    {
        $oAuthentication = new Authentication(['scenario' => Authentication::SCENARIO_SIGN_UP]);
        $oAuthorization = new Authorization(['scenario' => Authorization::SCENARIO_SIGN_UP]);
        $aData = [
            'u_mail' => $this->aTestValidData['sMailUser'],
            'u_password' => $this->aTestValidData['sPassword'],
            'u_name' => $this->aTestValidData['sName'],
            'u_surname' => $this->aTestValidData['sSurname'],
            'u_phone' => $this->aTestValidData['sPhone'],
        ];

        $oAuthentication->load($aData, '');

        $oAuthorization->load($this->aTestValidData, '');
        $oAuthorization->validate();

        expect($oAuthorization->defineUser()->toArray())->equals($oAuthentication->toArray());
    }
}