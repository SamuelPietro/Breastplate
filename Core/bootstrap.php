<?php

namespace Core;

define('DS', DIRECTORY_SEPARATOR);

// Captura o path completo do aplicativo. DIRECTORY_SEPARATOR adiciona uma barra ao final do path
define('ROOT', dirname(__DIR__) . DS);

// Captura a pasta do projeto: path full mais src, como '/var/www/html/base/src'.
define('APP', ROOT . 'App' . DS);
define('CORE', ROOT . 'Core' . DS);
define('VIEW', ROOT . 'Resources' . DS . 'Views' . DS);

// Este é o auto-loader para as dependências do Composer (para atualizar o namespace em seu projeto execute: composer dumpautoload).
require_once ROOT . 'vendor/autoload.php';

// Carregar as configurações da aplicação (error reporting etc.)
require_once CORE . 'config.php';


// Iniciar a aplicação através do Router
new Router();
