<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\AuthService;

session_start();

// Initialize the AuthService
$db = new \App\Database();
$authService = new AuthService($db);

// Call the logout method
$authService->logout();


header('Location: index.php');
exit;