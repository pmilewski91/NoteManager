<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Database;
use App\Service\AuthService;
use App\Model\User;
use App\Service\ValidationService;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    $user = new User($password, $email);

    if ($email && $password && $confirm) {
        if (!ValidationService::validateEmail($email)) {
            $message = 'Podaj poprawny adres e-mail.';
        } elseif ($password !== $confirm) {
            $message = 'Hasła się nie zgadzają.';
        } elseif (!ValidationService::validatePassword($password)) {
            $message = 'Hasło musi mieć co najmniej 4 znaki.';
        
        } else {
            $db = new Database();
            $authService = new AuthService($db);
            if ($authService->checkIfUserExists($email)) {
                $message = 'Użytkownik o podanym adresie e-mail już istnieje.';
            }else{
                $result = $authService->register($email, $password);

                if (is_object($result)) {
                    $message = 'Rejestracja zakończona sukcesem! Możesz się zalogować.';
                } else {
                    $message = 'Wystąpił błąd podczas rejestracji.';
                }
            }
            
        }
    } else {
        $message = 'Wypełnij wszystkie pola.';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="login-box bg-white p-4 rounded shadow" style="max-width:400px;margin:60px auto;">
        <h1 class="mb-4 text-center">Rejestracja</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Hasło:</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="4">
            </div>
            <div class="mb-3">
                <label for="confirm" class="form-label">Powtórz hasło:</label>
                <input type="password" id="confirm" name="confirm" class="form-control" required minlength="4">
            </div>
            <button type="submit" class="btn btn-primary w-100">Zarejestruj się</button>
            <p class="mt-3 text-center">Masz już konto? <a href="index.php">Zaloguj się</a></p>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>