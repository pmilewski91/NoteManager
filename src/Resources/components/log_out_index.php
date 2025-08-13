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