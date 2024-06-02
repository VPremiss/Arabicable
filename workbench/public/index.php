<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Set environment variable for debugging
putenv('APP_DEBUG=true');
putenv('APP_KEY=base64:XODlDvpTV2I6VfK0zis6yg+N2XJl0Ho8E2mxcAWsDIA=');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
