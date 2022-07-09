<?php

define('APP_NAME', "PFrame"); // Nome da aplicação
define('APP_DESC', 'Um framework PHP usando o padrão MVC'); // Descrição da aplicação
define('APP_KEYS', 'mvc, php, framework, dao'); // Keyords da aplicação (Seperado por virgulas).
define('APP_URL', 'http://localhost/'); // Endereço base da aplicação
define('APP_AUTHOR', 'Samuel Pietro'); // Author da aplicação

define('DB_TYPE', 'mysql'); // mysql or pgsql
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'face');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');// 3306 or 5432
define('DB_CHARSET', 'utf8mb4');

define('URL_PUBLIC_FOLDER', 'public'); // public
define('URL_PROTOCOL', '//'); // //
define('URL_DOMAIN', $_SERVER['HTTP_HOST']); // localhost
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);// /localhost/appfolder/
define('DEFAULT_CONTROLLER', 'app');
define('DEBUG', true);


if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'errors.log');
}
