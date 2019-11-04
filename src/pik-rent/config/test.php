<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@accessTokenLifeTime' => 30,
        '@phoneConfirmationCodeLifeTime' => 3600,
        '@freezingTimeDuringPhoneProof' => 120,
        '@bannedTimeDuringPhoneProof' => 86400,
        '@freezingCookieName' => 'freeze_from_calltracking',
        '@regExpPhoneNumber' => '/^7[0-9]{10}$/i',
        '@regExpUserPassword' => '/^[a-zA-Z_0-9@$!%*#?&^*]{6,}$/i',
        '@regExpUserNameSurname' => '/^[a-zA-Z-А-Яа-я]*$/iu',
    ],
    'bootstrap' => [
        'kint'
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'identityClass' => 'app\components\user\Authentication',
        ],
        'spellChecker' => ['class' => 'app\components\spell_check\SpellChecker'],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
            // but if you absolutely need it set cookie domain to localhost
         /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
    'modules' => [

        'kint' => [
            'class' => 'digitv\kint\Module',
        ],

    ]
];
