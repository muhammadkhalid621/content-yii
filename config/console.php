<?php
return [
  'id' => 'cms-prototype-console',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'app\\commands',
  'components' => [
    'db' => require __DIR__ . '/db.php',
    'authManager' => [
      'class' => yii\rbac\DbManager::class,
    ],
    'log' => [
      'targets' => [
        [
          'class' => yii\log\FileTarget::class,
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
  ],
];
