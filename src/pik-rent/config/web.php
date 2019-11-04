<?php

use yii\web\Response;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'api/search',
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
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
//                'application/xml' => Response::FORMAT_XML,
            ],
            'languages' => [
                'en',
                'de',
            ],
        ],
        'log',
        'kint'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Ifdsafds7887dfs8o4lR2VstkunLGr8GGzk1mm6jHVUNXafds',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                if (!YII_DEBUG && Yii::$app->errorHandler->exception) {
                    $sError = Yii::$app->errorHandler->exception->getMessage();
                    $mJson = json_decode($sError, true);
                    $event->sender->data = !$mJson ? ['error' => $sError] : $mJson;
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\components\user\Authentication',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'prefix' => function ($message) {
                        return "";
                    },
                    'levels' => ['info'],
                    'categories' => ['api_controller'],
                    'logFile' => '@app/runtime/logs/api_controller.log',
                    'logVars' => [],
                    'maxFileSize' => 10240,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'db' => $db,
        'spellChecker' => ['class' => 'app\components\spell_check\SpellChecker']
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
    'modules' => [

        'kint' => [
            'class' => 'digitv\kint\Module',
        ],

    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
