<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],//,'Init'
    'components' => [
        //'Init' => ['class'=>'app\components\Init'],
        'ListItem' => ['class'=>'app\components\ListItem'],
        'HelperFunc' => ['class'=>'app\components\HelperFunc'],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a4s5d6as4d56',
            'baseUrl' => '/',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'loginUrl'=>['/login'],
            //'allowAutoLogin'=>true,
            'identityClass' => 'app\models\UserIdentity',
            'enableAutoLogin' => false
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => '/',
            'rules' => [
            'index' => 'site/index',
            // 'about' => 'site/about',
            // 'contact' => 'site/contact',
            'login' => 'site/login',
            'logout' => 'site/logout',
            'settings' => 'site/settings',
            'report' => 'site/report',
            'smsreport' => 'site/smsreport',
            'userreport' => 'site/userreport',
            'recognition' => 'site/recognition',
            'issuanceofcredit' => 'site/issuanceofcredit',
            'getautocomplete' => 'site/getautocomplete',
            'getdata' => 'site/getdata',
            'updatetemplate' => 'site/updatetemplate',
            'gettemplate' => 'site/gettemplate',
            'getprintpreviewdata' => 'site/getprintpreviewdata',
            'calcaction' => 'site/calcaction',
            'deleteaction' => 'site/deleteaction',
            'recognitionajax' => 'site/recognitionajax',
            'getrecognitionajax' => 'site/getrecognitionajax',
            'getuserreportajax' => 'site/getuserreportajax',
            'searchajax' => 'site/searchajax',
            'gethistoryajax' => 'site/gethistoryajax',
            'getlibajax' => 'site/getlibajax',
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['10.240.101.23','127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['10.240.101.23','127.0.0.1', '::1'],
    ];
}

return $config;
