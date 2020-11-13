<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=mail',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => fn($event) => $event->sender->createCommand("set datestyle = 'German,DMY'")->execute(),
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
