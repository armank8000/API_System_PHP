<?php

use PH7\Learnphp\validation\exception\NotFoundException;

$resource = $_REQUEST['resource'] ?? null;

try{
    return match ($resource) {
        'user' => require_once 'UserAction.php',
        'foodItem' => require_once 'foodAction.php',
        default => require_once 'not-found.routes.php',
    };
}catch(NotFoundException $e){
    return require_once 'not-found.routes.php';
}