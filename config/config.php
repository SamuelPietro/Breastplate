<?php

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

return [
    'APP_NAME' => 'PFrame',
    'APP_DESC' => 'Um framework PHP usando o padrão MVC',
    'APP_KEYS' => 'mvc, php, framework',
    'APP_AUTHOR' => 'Samuel Pietro',
    'DEBUG' => true,
    'LANG' => 'en.UTF-8',
    'APP_LANGUAGE' => 'english',
    'BASE_URL' => $protocol . '://' . $host,
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'pframe',
    'DB_USER' => 'root',
    'DB_PASS' => 'root',
    'DB_PORT' => 3306,
    'DB_CHARSET' => 'utf8mb4',
    'DB_SSL_CA_FILE' => '/resources/ssl/database.crt.pem',
    'DB_VERIFY_CERT' => false,
];