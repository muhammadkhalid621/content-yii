<?php

$params = [];
if (file_exists(__DIR__ . '/params.php')) {
    $params = require __DIR__ . '/params.php';
}

$config = [
    'id' => 'cms-prototype',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'changeme-cookie-key',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
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
        'db' => require __DIR__ . '/db.php',
        'session' => [
            'name' => 'CMSSESSID',     // custom cookie name so it’s easy to see
            'cookieParams' => [
                'httponly' => true,
                'secure' => false,     // keep false if you’re on http://localhost
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX, // avoids cross-site drops
            ],
            'timeout' => 3600,         // 1 hour
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,   // allows “remember me”
            'loginUrl' => ['/site/login'],
        ],

        // ✅ Add this block to fix asset publishing errors
        'assetManager' => [
            'bundles' => [
                // Force Yii to use CDN and not publish from @bower
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null, // <— important
                    'js' => ['https://code.jquery.com/jquery-3.7.1.min.js'],
                ],
                'yii\bootstrap5\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => ['https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'],
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js',
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js',
                    ],
                    'depends' => ['yii\web\JqueryAsset'],
                ],
            ],
            'appendTimestamp' => true,
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET,POST api/v1/<controller:[\w\-]+>' => 'api/v1/<controller>/index',
                'GET api/v1/<controller:[\w\-]+>/<id:\d+>' => 'api/v1/<controller>/view',
                'POST api/v1/<controller:[\w\-]+>/<action>' => 'api/v1/<controller>/<action>',
                'PUT,PATCH api/v1/<controller:[\w\-]+>/<id:\d+>' => 'api/v1/<controller>/update',
                'DELETE api/v1/<controller:[\w\-]+>/<id:\d+>' => 'api/v1/<controller>/delete',
            ],
        ],
        'tenant' => [
            'class' => 'app\components\TenantContext',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class' => 'app\modules\api\modules\v1\Module',
                ],
            ],
        ],
    ],
    'params' => $params,
];

return $config;
