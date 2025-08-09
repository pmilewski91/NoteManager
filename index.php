<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Database;
use App\Repository\NoteRepository;


$db = new Database();
$repo = new NoteRepository($db);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>NoteManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .note-card { box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .login-box { max-width: 400px; margin: 60px auto; }
        small { font-size: 0.5em; color: #6c757d; }
    </style>
</head>
<body>
<div class="container py-5">
    <?php if (isset($_SESSION['user'])): 
        $userId = $_SESSION['user']['id'];
        $notes = $repo->findByUserId($userId);
    ?>
        <h1 class="mb-4">Twoje notatki <small>(<?= $_SESSION['user']['email']; ?>)</small></h1>
        <hr>
        <div class="row">
            <?php foreach ($notes as $note): ?>
                <div class="col-md-4 mb-4">
                    <div class="card note-card" style="background-color: <?= htmlspecialchars($note->getColor()) ?>;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($note->getTitle()) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($note->getDescription())) ?></p>
                            <button class="btn btn-sm btn-danger mt-2 remove-note-btn" data-id="<?= $note->getId() ?>">Usuń</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="logout.php" class="btn btn-outline-danger mt-3">Wyloguj się</a>
        <a href="createNote.php" class="btn btn-primary mt-3">Dodaj nową notatkę</a>
        <script>
            document.querySelectorAll('.remove-note-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Czy na pewno chcesz usunąć tę notatkę?')) {
                        fetch('removeNote.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(this.dataset.id)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                window.location.reload();
                            } else {
                                alert(result.error || 'Błąd usuwania notatki');
                            }
                        })
                        .catch(() => {
                            alert('Wystąpił błąd serwera');
                        });
                    }
                });
            });
        </script>
    <?php else: ?>
        <div class="login-box bg-white p-4 rounded shadow">
            <h1 class="mb-4 text-center">Logowanie</h1>
            <p>Login: demo@demo.pl</br>Hasło: demo</p>
            <hr>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło:</label>
                    
                    <input type="password" id="password" name="password" class="form-control" required minlength="4">
                   
                </div>
                <div id="loginError" class="text-danger mb-3" style="display:none;"></div>
                <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
                <p class="mt-3 text-center">Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
            </form>
        </div>
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);
                fetch('login.php', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.location.reload();
                    } else {
                        document.getElementById('loginError').textContent = result.error || 'Błąd logowania';
                        document.getElementById('loginError').style.display = 'block';
                    }
                })
                .catch(() => {
                    document.getElementById('loginError').textContent = 'Wystąpił błąd serwera';
                    document.getElementById('loginError').style.display = 'block';
                });
            });
        </script>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>