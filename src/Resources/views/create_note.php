
<div class="container py-5 text-center">
    <h1 class="mb-4">Dodaj nową notatkę</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" class="m-auto bg-white p-4 rounded shadow" style="max-width:500px;">
        <div class="mb-3">
            <label for="title" class="form-label">Tytuł</label>
            <input type="text" name="title" id="title" class="form-control" required maxlength="100">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea name="description" id="description" class="form-control" rows="4" required maxlength="1000"></textarea>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Kolor</label>
            <select name="color" id="color" class="form-select">
                <option value="white">Biały</option>
                <option value="yellow">Żółty</option>
                <option value="green">Zielony</option>
                <option value="blue">Niebieski</option>
                <option value="pink">Różowy</option>
                <option value="grey">Szary</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Dodaj notatkę</button>
    </form>
    <a href="index.php" class="btn btn-link mt-3">Powrót do notatek</a>
</div>