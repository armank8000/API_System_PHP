<?php
namespace PH7\Learnphp;

use PH7\PhpHttpResponseHeader\Exception;

require __DIR__ . '/vendor/autoload.php';
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
$whoops->register();

require __DIR__ . '/src/helpers/headers.php';
require __DIR__ . '/src/config/config.inc.php';
require __DIR__ . '/src/config/database.inc.php';
require __DIR__ .'/src/routes/routes.php';

