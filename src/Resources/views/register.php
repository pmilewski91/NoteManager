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