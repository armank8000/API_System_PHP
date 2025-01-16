<?php

$resource = $_REQUEST['resource'] ?? null;

switch ($resource) {
    case 'user':

        return require_once 'UserAction.php';
        default:
            return require_once 'main.routes.php';
    }