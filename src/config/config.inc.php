<?php

namespace PH7\LearnPHP\Config;


use Dotenv\Dotenv;

enum Environment: string{
    case PRODUCTION = 'production';
    case DEVELOPMENT = 'development';

    public function environmentName():string{
        return match ($this) {
            self::PRODUCTION => 'production',
            self::DEVELOPMENT => 'development',

        };
    }


}







$path = dirname((__DIR__),2);
$dotenv = Dotenv::createImmutable($path);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);