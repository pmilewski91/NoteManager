<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\Routing;
use App\Controller\NoteController;

// Initialize routing
Routing::route(new NoteController(), 'delete');