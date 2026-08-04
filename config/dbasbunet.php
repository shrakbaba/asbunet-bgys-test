<?php

$asbunetDbConfig = require __DIR__ . '/db.php';
$asbunetDbConfig['dsn'] = preg_replace('/dbname=([^;]+)/', 'dbname=yii', $asbunetDbConfig['dsn']);

return $asbunetDbConfig;
