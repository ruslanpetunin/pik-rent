<?php

namespace tests\unit\models;

use app\components\user\Authentication;
use app\models\active\AccessToken;
use Codeception\Test\Unit;

class AccessTokenTest extends Unit
{
    public function testCreating()
    {
        $oUser = $this->createUser();
        $oAccessToken = new AccessToken(['scenario' => AccessToken::SCENARIO_CREATE_TEMPORARY]);

        expect($oAccessToken->load(['ac_user_id' => $oUser->u_id], ''))->true();
        expect($oAccessToken->save())->true();
        expect($oAccessToken->ac_user_id)->equals($oUser->u_id);
        expect($oAccessToken->ac_expiration_date)->notNull();
        expect(strlen($oAccessToken->ac_token))->equals(40);
    }

    public function createUser()
    {
        $oUser = new Authentication(['scenario' => Authentication::SCENARIO_SIGN_UP]);

        expect(
            $oUser->load([
                'u_mail' => 'petunin.ruslan@yandex.ru',
                'u_password' => 'fdw3r45Rrr4#22',
                'u_name' => 'Ruslan',
                'u_surname' => 'Petunin',
                'u_phone' => '79779367874',
            ], '')
        )->true();
        expect($oUser->validate())->true();
        expect($oUser->save())->true();

        return $oUser;
    }
}