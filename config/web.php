<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$container = require __DIR__ . '/container.php';
$cookiesParams = [
    'httpOnly' => true,
    'sameSite' => PHP_VERSION_ID >= 70300 ? \yii\web\Cookie::SAME_SITE_STRICT : null,
    'secure' => !YII_ENV_DEV,
];


$config = [
    'id' => 'basic',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'container' => $container,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gfCaW2s20fMZpZ_3GW0BNIXdse85BrZI',
            'csrfCookie' => $cookiesParams,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'cookieParams' => $cookiesParams,
            'timeout' => 172800,
        ],
        'user' => [
            'identityClass' => app\security\UserIdentity::class,
            'enableAutoLogin' => true,
            'authTimeout' => 172800,
            'absoluteAuthTimeout' => 2592000,
            'identityCookie' => array_merge(['name' => '_identity'], $cookiesParams),
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => ['it-animal@yandex.ru' => 'Почта'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'it-animal@yandex.ru',
                'password' => 'animal2020',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\PgsqlMutex::class, // Mutex used to sync queries
        ],
    ],
    'params' => $params,
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            'downloadAction' => 'gridview/export/download',
        ],
    ],
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
