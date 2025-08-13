<?php

require_once __DIR__ . '/vendor/autoload.php';


use App\Service\Routing;
use App\Controller\RegisterController;

// Initialize routing
Routing::route(new RegisterController(), 'register');