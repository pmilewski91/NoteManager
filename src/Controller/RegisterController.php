<?php

namespace App\Controller;

use App\Database;
use App\Service\AuthService;
use App\Service\ValidationService;

class RegisterController
{
    private string $message = '';
    private array $formData = [
        'email' => '',
        'password' => '',
        'confirm' => ''
    ];
    private AuthService $authService;

    public function __construct()
    {
        $db = new Database();
        $this->authService = new AuthService($db);
    }

    /**
     * [Sets a message to be displayed to the user.]
     *
     * @param string $message
     * 
     * @return void
     * 
     */
    private function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * [Checks if the form data is valid and sets the form data.]
     *
     * @return bool
     * 
     */
    private function checkDataForm(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        try {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirm = $_POST['confirm'] ?? null;

            if (!$email || !$password || !$confirm) {
                $this->setMessage('Wypełnij wszystkie pola.');
                return false;
            }

            if ($password !== $confirm) {
                $this->setMessage('Hasła się nie zgadzają.');
                return false;
            }

            ValidationService::validateEmail($email);
            ValidationService::validatePassword($password);

            $this->setFormData($email, $password, $confirm);
            return true;
        } catch (\InvalidArgumentException $e) {
            $this->setMessage($e->getMessage());
            return false;
        }
    }

    /**
     * [Sets the form data for registration.]
     *
     * @param string $email
     * @param string $password
     * @param string $confirm
     * 
     * @return void
     * 
     */
    private function setFormData(string $email, string $password, string $confirm): void
    {
        $this->formData['email'] = htmlspecialchars(strip_tags(trim($email)), ENT_QUOTES, 'UTF-8');
        $this->formData['password'] = $password;
        $this->formData['confirm'] = $confirm;
    }

    /**
     * [Registers a new user in the database.]
     *
     * @return void
     * 
     */
    public function register(): void
    {
        if ($this->checkDataForm()) {
            try {
                if ($this->authService->checkIfUserExists($this->formData['email'])) {
                    $this->setMessage('Użytkownik o podanym adresie e-mail już istnieje.');
                } else {
                    $this->authService->register(
                        $this->formData['email'],
                        $this->formData['password']
                    );
                    $this->setMessage('Rejestracja zakończona sukcesem! Możesz się zalogować.');
                }
            } catch (\Exception $e) {
                $this->setMessage('Wystąpił błąd podczas rejestracji.');
            }
        }

        $message = $this->message;
        $content = __DIR__ . '/../Resources/views/register.php';
        require_once __DIR__ . '/../Resources/layouts/default.php';
    }
}
