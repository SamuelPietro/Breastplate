<?php

const APP_NAME = "PFrame"; // Nome da aplicação
const APP_DESC = 'Um framework PHP usando o padrão MVC'; // Descrição da aplicação
const APP_KEYS = 'mvc, php, framework, dao'; // Keyords da aplicação (Seperado por virgulas).
const APP_URL = 'http://localhost/'; // Endereço base da aplicação
const APP_AUTHOR = 'Samuel Pietro'; // Author da aplicação

const DB_TYPE = 'mysql'; // mysql or pgsql
const DB_HOST = '127.0.0.1';
const DB_NAME = 'face';
const DB_USER = 'root';
const DB_PASS = '';
const DB_PORT = '3306';// 3306 or 5432
const DB_CHARSET = 'utf8mb4';

const URL_PUBLIC_FOLDER = 'public'; // public
const URL_PROTOCOL = '//'; // //
define('URL_DOMAIN', $_SERVER['HTTP_HOST']); // localhost
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
const URL = URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER;// /localhost/appfolder/
const DEFAULT_CONTROLLER = 'app';
const DEBUG = true;


if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'errors.log');
}
