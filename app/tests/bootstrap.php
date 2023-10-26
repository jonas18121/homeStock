<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} else {
    $dotenv = new Dotenv();
    $dotenv->loadEnv(dirname(__DIR__).'/.env');
}
