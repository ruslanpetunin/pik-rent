<?php

namespace tests\unit\models;

use app\components\user\Authentication;
use app\models\active\AccessToken;
use Codeception\Test\Unit;
use Yii;

class UserTest extends Unit
{
    protected $aUserRegister = [
        'u_mail' => 'petunin.ruslan@yandex.ru',
        'u_password' => 'fdw3r45Rrr4#22',
        'u_name' => 'Ruslan',
        'u_surname' => 'Petunin',
        'u_cookie_auth' => 'fdfjireei09i984',
        'u_phone' => '79779367874'
    ];

    protected $aUserUpdated = [
        'u_mail' => 'petunin2.ruslan@yandex.ru',
        'u_password' => 'fdw3r45Rrr4#223',
        'u_name' => 'ale',
        'u_surname' => 'fdsfa',
        'u_cookie_auth' => 'fdfjireei09i914',
        'u_phone' => '79779367875'
    ];

    protected $aUserInvalid = [
        'u_mail' => 'petuni n2.ruslan@yandex.ru',
        'u_password' => 'fdw ',
        'u_name' => 'Adsaewrew fre',
        'u_surname' => 'fdsafdas frew',
        'u_phone' => '89779367875'
    ];

    public function testRegisterScenarioUser()
    {
        $oUser = $this->createUser();
        expect(Yii::$app->getSecurity()->validatePassword($this->aUserRegister['u_password'], $oUser->u_password))->true();
        expect($oUser->u_cookie_auth)->notNull();
        expect($oUser->u_mail)->equals($this->aUserRegister['u_mail']);
        expect($oUser->u_name)->equals($this->aUserRegister['u_name']);
        expect($oUser->u_surname)->equals($this->aUserRegister['u_surname']);
        expect($oUser->u_phone)->equals($this->aUserRegister['u_phone']);
        expect($oUser->u_cookie_auth)->notEquals($this->aUserRegister['u_cookie_auth']);
    }

    public function testUpdateScenarioUser()
    {
        $this->createUser();

        $oUserAfterUpdate = Authentication::findOne(['u_mail' => $this->aUserRegister['u_mail']]);
        $oUserAfterUpdate->scenario = Authentication::SCENARIO_UPDATE;

        expect($oUserAfterUpdate->load($this->aUserUpdated, ''))->true();
        expect($oUserAfterUpdate->validate())->true();
        expect($oUserAfterUpdate->save())->true();
        expect(Yii::$app->getSecurity()->validatePassword($this->aUserUpdated['u_password'], $oUserAfterUpdate->u_password))->true();
        expect($oUserAfterUpdate->u_cookie_auth)->notNull();
        expect($oUserAfterUpdate->u_mail)->equals($this->aUserUpdated['u_mail']);
        expect($oUserAfterUpdate->u_name)->equals($this->aUserUpdated['u_name']);
        expect($oUserAfterUpdate->u_surname)->equals($this->aUserUpdated['u_surname']);
        expect($oUserAfterUpdate->u_phone)->equals($this->aUserUpdated['u_phone']);
        expect($oUserAfterUpdate->u_cookie_auth)->notEquals($this->aUserUpdated['u_cookie_auth']);
    }

    public function testUpdateWithoutPasswordScenarioUser()
    {
        $this->createUser();

        $oUserAfterUpdate = Authentication::findOne(['u_mail' => $this->aUserRegister['u_mail']]);
        $oUserAfterUpdate->scenario = Authentication::SCENARIO_UPDATE_WITHOUT_PASSWORD;

        expect($oUserAfterUpdate->load($this->aUserUpdated, ''))->true();
        expect($oUserAfterUpdate->validate())->true();
        expect($oUserAfterUpdate->save())->true();
        expect(Yii::$app->getSecurity()->validatePassword($this->aUserRegister['u_password'], $oUserAfterUpdate->u_password))->true();
        expect($oUserAfterUpdate->u_cookie_auth)->notNull();
        expect($oUserAfterUpdate->u_mail)->equals($this->aUserUpdated['u_mail']);
        expect($oUserAfterUpdate->u_name)->equals($this->aUserUpdated['u_name']);
        expect($oUserAfterUpdate->u_surname)->equals($this->aUserUpdated['u_surname']);
        expect($oUserAfterUpdate->u_phone)->equals($this->aUserUpdated['u_phone']);
        expect($oUserAfterUpdate->u_cookie_auth)->notEquals($this->aUserUpdated['u_cookie_auth']);
    }

    public function testFindIdentity()
    {
        $oUser = $this->createUser();

        expect(Authentication::findIdentity($oUser->u_id)->toArray())->equals($oUser->toArray());
    }

    public function testFindIdentityByAccessToken()
    {
        $oUser = $this->createUser();
        $oAccessToken = new AccessToken(['scenario' => AccessToken::SCENARIO_CREATE_TEMPORARY]);

        $oAccessToken->load(['ac_user_id' => $oUser->u_id], '');
        $oAccessToken->save();

        expect(Authentication::findIdentityByAccessToken($oAccessToken->ac_token)->toArray())->equals($oUser->toArray());
    }

    public function testGetId()
    {
        $oUser = $this->createUser();
        Yii::$app->user->login($oUser);

        expect(Yii::$app->user->id)->equals($oUser->u_id);
    }

    public function testAuthKey()
    {
        $oUser = $this->createUser();
        Yii::$app->user->login($oUser);

        expect($oUser->getAuthKey())->equals($oUser->u_cookie_auth);
    }

    public function testSignUpWithInvalidData()
    {
        $oUser = new Authentication(['scenario' => Authentication::SCENARIO_SIGN_UP]);

        expect($oUser->load($this->aUserInvalid, ''))->true();
        expect($oUser->validate())->false();
        expect($oUser->errors['u_mail'][0])->equals("The mail is invalid email address");
        expect($oUser->errors['u_name'][0])->equals("name is invalid.");
        expect($oUser->errors['u_surname'][0])->equals("surname is invalid.");
        expect($oUser->errors['u_password'][0])->equals("password is invalid.");
        expect($oUser->errors['u_phone'][0])->equals("phone is invalid.");
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
