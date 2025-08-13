<?php

require_once __DIR__ . '/vendor/autoload.php';


use App\Service\Routing;
use App\Controller\LoginController;

// Initialize routing
Routing::route(new LoginController(), 'logout');