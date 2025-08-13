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

        try {
            $input = $this->validateAndSanitizeInput();
            
            if (!$input) {
                $this->jsonResponse(false, 'Nieprawidłowe dane logowania.');
                return;
            }

            $user = $this->authService->login($input['email'], $input['password']);
            
            if (!$user) {
                $this->jsonResponse(false, 'Nieprawidłowy adres e-mail lub hasło.');
                return;
            }

            $this->jsonResponse(true);

        } catch (\Exception $e) {
            $this->jsonResponse(false, 'Wystąpił błąd podczas logowania.');
        }
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

    /**
     * [Validates and sanitizes the input data.]
     *
     * @return array|null
     * 
     */
    private function validateAndSanitizeInput(): ?array 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $email = filter_var(
            trim($_POST['email'] ?? ''), 
            FILTER_SANITIZE_EMAIL
        );
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            return null;
        }

        return ['email' => $email, 'password' => $password];
    }

    /**
     * [Sends a JSON response.]
     *
     * @param bool $success
     * @param string|null $error
     * @return void
     * 
     */
    private function jsonResponse(bool $success, ?string $error = null): void
    {
        echo json_encode([
            'success' => $success,
            'error' => $error
        ]);
        exit;
    }
}
