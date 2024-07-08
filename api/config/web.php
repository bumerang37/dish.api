<?php

$db = require __DIR__ . '/db.php';
$params = array_merge(
    require(__DIR__ . '/../../config/params.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


$config = [
    'id' => 'dish.api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'app\api\modules\v1\Module'
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'db' => $db,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '/',
                    'route' => 'dish/index',
                    'defaults' => ['code' => 'dcii']
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/ingredient','v1/ingredient-type']],
//                ['class' => 'yii\rest\UrlRule', 'controller' => 'ingredient'],
//                ['class' => 'yii\rest\UrlRule', 'controller' => 'ingredient-type'],
                'GET dish' => 'dish/index',
                'GET test' => 'dish/test',
            ],
        ]
    ],
    'params' => $params,
];