<?php


use Src\Core\Autoloader;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

// Carrega o autoload do Composer
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

// Realiza a depuração de erros via filp/whoops
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$cache = new FilesystemAdapter();

$autoloader = new Autoloader();
$autoloader->register();

// Define constantes do sistema
define('BASE_URL', getenv('BASE_URL'));
const VIEWS_PATH =  __DIR__ . '/../../app/Views/';

$rotas = 'routes.php';
require $rotas;


// Inclui as funções auxiliares
require_once 'helpers.php';
