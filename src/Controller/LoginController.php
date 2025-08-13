<?php

namespace App\Controller;

use App\Database;
use App\Service\AuthService;

class LoginController
{

    private AuthService $authService;

    public function __construct()
    {
        $db = new Database();
        $this->authService = new AuthService($db);
    }

    /**
     * [Renders the login page and handles login requests.]
     *
     * @return void
     * 
     */
    public function login(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($email && $password) {

                $user = $this->authService->login($email, $password);

                if ($user) {
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
    }

    /**
     * [Logs out the user by clearing the session.]
     *
     * @return void
     * 
     */
    public function logout(): void
    {
        AuthService::logout();
    }
}
