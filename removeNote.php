<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Database;
use App\Repository\NoteRepository;

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Brak autoryzacji.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noteId = $_POST['id'] ?? null;

    if ($noteId) {
        $db = new Database();
        $repo = new NoteRepository($db);

        
        $note = $repo->findById((int)$noteId);
        if ($note && $note->getUserId() == $_SESSION['user']['id']) {
            $repo->delete((int)$noteId);
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Brak uprawnień do usunięcia notatki.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Brak ID notatki.']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Nieprawidłowe żądanie.']);