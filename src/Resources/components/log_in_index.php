<h1 class="mb-4">Twoje notatki</h1>
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
<a href="createNote.php" class="btn btn-primary mt-3">Dodaj nową notatkę</a>
<a href="logout.php" class="btn btn-outline-danger mt-3">Wyloguj się</a>
<script>
    document.querySelectorAll('.remove-note-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Czy na pewno chcesz usunąć tę notatkę?')) {
                fetch('removeNote.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
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