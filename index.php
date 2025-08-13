<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\Routing;
use App\Controller\HomeController;
use App\Service\AuthService;

// Initialize routing
Routing::route(new HomeController(), 'index');
