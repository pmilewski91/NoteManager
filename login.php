<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Database;
use App\Service\AuthService;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {
        $db = new Database();
        $authService = new AuthService($db);

        $user = $authService->login($email, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ];
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Nieprawidłowy adres e-mail lub hasło.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Brak danych logowania.']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Nieprawidłowe żądanie.']);