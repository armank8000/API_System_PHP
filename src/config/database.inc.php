<?php
namespace ph7\LearnPHP\config;
use RedBeanPHP\R;

$dsn = sprintf("mysql:host=%s;dbname=%s", $_ENV['DB_HOST'], $_ENV['DB_NAME']);
R::setup($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);