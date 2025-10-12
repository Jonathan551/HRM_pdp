<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=hrm_pdp',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function ($event) {
      $event->sender->createCommand("SET time_zone = '+07:00'")->execute();
    },
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
