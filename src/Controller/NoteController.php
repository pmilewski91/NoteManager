<?php

namespace App\Controller;

use App\Database;
use App\Model\Note;
use App\Repository\NoteRepository;
use App\Service\AuthService;
use App\Service\LayoutService;

class NoteController
{
    private string $message = '';
    private array $formData = [
        'title' => '',
        'description' => '',
        'color' => 'white'
    ];
    private NoteRepository $noteRepository;

    public function __construct()
    {
        $db = new Database();
        $this->noteRepository = new NoteRepository($db);
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
     *  [Checks if the form data is valid and sets the form data.]
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
            $title = $_POST['title'] ?? null;
            $description = $_POST['description'] ?? null;
            $color = $_POST['color'] ?? 'white';

            if (!$title || !$description) {
                $this->setMessage('Wypełnij wszystkie pola.');
                return false;
            }

            $this->setFormData($title, $description, $color);
            return true;
        } catch (\InvalidArgumentException $e) {
            $this->setMessage($e->getMessage());
            return false;
        }
    }

    /**
     * [Sets the form data for creating a note.]
     *
     * @param string $title
     * @param string $description
     * @param string $color
     * 
     * @return void
     * 
     */
    private function setFormData(string $title, string $description, string $color): void
    {
        $this->formData['title'] = htmlspecialchars($title);
        $this->formData['description'] = htmlspecialchars($description);
        $this->formData['color'] = $color;
    }

    /**
     * [Creates a new note and saves it to the database.]
     *
     * @return void
     * 
     */
    public function create(): void
    {
        if (!AuthService::checkIfLogging()) {
            header('Location: index.php');
            exit;
        }

        if ($this->checkDataForm()) {
            $userId = $_SESSION['user']['id'] ?? null;
            if ($userId) {
                $note = new Note(
                    $this->formData['title'],
                    $this->formData['description'],
                    $userId,
                    $this->formData['color']
                );
                $this->noteRepository->create($note);
                $this->setMessage('Notatka została utworzona!');
            } else {
                $this->setMessage('Błąd: Użytkownik nie jest zalogowany.');
            }
        }

        $message = $this->message;
        $content = __DIR__ . '/../Resources/views/create_note.php';
        require_once LayoutService::renderLayout();
    }

    /**
     * [Deletes a note by its ID if the user is logged in and has permission.]
     *
     * @return void
     * 
     */
    public function delete(): void
    {
        if (!AuthService::checkIfLogging()) {
            header('Location: index.php');
            exit;
        }
        $noteId = $_POST['id'] ?? null;

        if ($noteId) {

            $note = $this->noteRepository->findById((int)$noteId);
            if ($note && $note->getUserId() == $_SESSION['user']['id']) {
                $this->noteRepository->delete((int)$noteId);
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
}
