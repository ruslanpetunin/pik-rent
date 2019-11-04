<?php

namespace tests\unit\models;

use app\components\user\Authentication;
use app\models\active\Contact;
use Codeception\Test\Unit;
use Yii;

class ContactTest extends Unit
{
    protected $sPhone = '79779367874';

    protected $sMail = 'fafewew@yandex.ru';

    protected $aUserRegister = [
        'u_mail' => 'petunin.ruslan@yandex.ru',
        'u_password' => 'fdw3r45Rrr4#22',
        'u_name' => 'Ruslan',
        'u_surname' => 'Petunin',
        'u_cookie_auth' => 'fdfjireei09i984',
        'u_phone' => '79779367874'
];

    public function testCreateValidPhoneContact()
    {
        $oUser = $this->createUser();
        $oContact = new Contact(['scenario' => Contact::SCENARIO_PHONE_CONTACT]);

        expect($oContact->load(['c_contact' => $this->sPhone, 'c_user_id' => $oUser->u_id], ''))->true();
        expect($oContact->validate())->true();
        expect($oContact->save())->true();
        expect($oContact->c_contact)->equals($this->sPhone);
        expect($oContact->c_user_id)->equals($oUser->u_id);
        expect($oContact->c_type)->equals(Contact::CONTACT_PHONE_TYPE);
        expect($oContact->c_send_notify)->equals(1);
        expect($oContact->c_removed)->equals(0);
    }

    public function testCreateValidMailContact()
    {
        $oUser = $this->createUser();
        $oContact = new Contact(['scenario' => Contact::SCENARIO_MAIL_CONTACT]);

        expect($oContact->load(['c_contact' => $this->sMail, 'c_user_id' => $oUser->u_id], ''))->true();
        expect($oContact->validate())->true();
        expect($oContact->save())->true();
        expect($oContact->c_contact)->equals($this->sMail);
        expect($oContact->c_user_id)->equals($oUser->u_id);
        expect($oContact->c_type)->equals(Contact::CONTACT_MAIL_TYPE);
        expect($oContact->c_send_notify)->equals(1);
        expect($oContact->c_removed)->equals(0);
    }

    public function testInvalidPhoneContact()
    {
        $oUser = $this->createUser();
        $aInvalidPhones = ['89779367874', 'hfdsjghjhj43', '', '0', '7977936787', '797793678747'];

        for ($i = 0; $i < count($aInvalidPhones); $i++) {
            $oContact = new Contact(['scenario' => Contact::SCENARIO_PHONE_CONTACT]);
            $this->sPhone = $aInvalidPhones[$i];
            expect($oContact->load(['c_contact' => $this->sPhone, 'c_user_id' => $oUser->u_id], ''))->true();
            expect($oContact->validate())->false();
        }
    }

    public function testInvalidMailContact()
    {
        $oUser = $this->createUser();
        $aInvalidMails = ['fdsaf 32', 'fdsfds fre@fre.ru', '', 'fdsafdsaf', 'fdsfds@', 'fdsfds@fdsfds'];

        for ($i = 0; $i < count($aInvalidMails); $i++) {
            $oContact = new Contact(['scenario' => Contact::SCENARIO_MAIL_CONTACT]);
            $this->sMail = $aInvalidMails[$i];
            expect($oContact->load(['c_contact' => $this->sMail, 'c_user_id' => $oUser->u_id], ''))->true();
            expect($oContact->validate())->false();
        }
    }

    public function testCheckExistingPhone()
    {
        $oUser = $this->createUser();
        $oContact = new Contact(['scenario' => Contact::SCENARIO_PHONE_CONTACT]);

        expect($oContact->load(['c_contact' => $this->sPhone, 'c_user_id' => $oUser->u_id], ''))->true();
        expect($oContact->validate())->true();
        expect($oContact->save())->true();
        expect(!!Contact::find()->getPhone($this->sPhone)->one())->true();
    }

    public function createUser()
    {
        $oUser = new Authentication(['scenario' => Authentication::SCENARIO_SIGN_UP]);

        expect($oUser->load($this->aUserRegister, ''))->true();
        expect($oUser->validate())->true();
        expect($oUser->save())->true();

        return $oUser;
    }
}
