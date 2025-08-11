<?php

namespace App\Repository;

use App\Database;
use PDO;
use App\Model\Note;
use App\Service\Serializer;
use App\Service\ValidationService;


class NoteRepository
{
    private $db;
    private $noteTitle;
    private $noteDescription;
    private $noteColor;
    private $noteUserId;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }
    
 
    
    /**
     * [Extracts data from the Note object and sets it to the class properties.]
     *
     * @param Note $note
     * 
     * @return void
     * 
     */
    private function getAndSetNoteData(Note $note): void
    {
        ValidationService::validateNoteTitle($note->getTitle());
        ValidationService::validateNoteDescription($note->getDescription());
        ValidationService::validateUserId($note->getUserId());
        
        $this->noteTitle = $note->getTitle();
        $this->noteDescription = $note->getDescription();
        $this->noteColor = $note->getColor();
        $this->noteUserId = $note->getUserId();
    }

    /**
     * [Creates a new note in the database.]
     *
     * @param Note $note
     * 
     * @return bool
     * 
     */
    public function create(Note $note): bool
    {
        $this->getAndSetNoteData($note);

        $stmt = $this->db->prepare("INSERT INTO notes (title, description, color, user_id) VALUES (:title, :description, :color, :user_id)");
        $stmt->bindParam(':title', $this->noteTitle);
        $stmt->bindParam(':description', $this->noteDescription);
        $stmt->bindParam(':color', $this->noteColor);
        $stmt->bindParam(':user_id', $this->noteUserId);
        $success = $stmt->execute();
    
        if (!$success) {
            error_log("Błąd usuwania notatki o ID: $this->noteUserId. Treść błędu: " . json_encode($stmt->errorInfo()));
        }
        
        return $success;
    }

    /**
     * [Updates an existing note in the database.]
     *
     * @param Note $note
     * 
     * @return bool
     * 
     */
    public function update(Note $note): bool
    {
        $this->getAndSetNoteData($note);

        $stmt = $this->db->prepare("UPDATE notes SET title = :title, description = :description, color = :color WHERE id = :id");
        $stmt->bindParam(':title', $this->noteTitle);
        $stmt->bindParam(':description', $this->noteDescription);
        $stmt->bindParam(':color', $this->noteColor);
        $stmt->bindParam(':id', $this->noteUserId);
        $success = $stmt->execute();

        if (!$success) {
            error_log("Błąd aktualizacji notatki o ID: $this->noteUserId. Treść błędu: " . json_encode($stmt->errorInfo()));
        }
        return $success;
    }

    /**
     * [Deletes a note from the database by its ID.]
     *
     * @param int $id
     * 
     * @return bool
     * 
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $success = $stmt->execute();

        if (!$success) {
            error_log("Błąd usuwania notatki o ID: $id. Treść błędu: " . json_encode($stmt->errorInfo()));
        }
        return $success;
    }

    /**
     * [Finds a note by its ID and returns it as a Note object.]
     *
     * @param int $id
     * 
     * @return Note|null
     * 
     */
    public function findById(int $id): ?Note
    {
        $stmt = $this->db->prepare("SELECT id, title, description, color, user_id, created_at FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $success = $stmt->execute();

        if (!$success) {
            error_log("Błąd pobierania notatki o ID: $id. Treść błędu: " . json_encode($stmt->errorInfo()));
            return null;
        }

        $noteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_int($noteData['id'])) {
            return Serializer::deserialize($noteData, Note::class);
        }
        
        return null;
    }

    /**
     * [Finds all notes in the database and returns them as an array of Note objects.]
     *
     * @return array
     * 
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, title, description, color, created_at FROM notes ORDER BY created_at DESC");
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, Note::class);
        
    }

    /**
     * [Finds all notes for a specific user by their user ID and returns them as an array of Note objects.]
     *
     * @param int $userId
     * 
     * @return array
     * 
     */
    public function findByUserId(int $userId): array
        {
            $stmt = $this->db->prepare("SELECT id, title, description, color, user_id, created_at FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindParam(':user_id', $userId);
            $success = $stmt->execute();

            if (!$success) {
                error_log("Błąd pobierania notatek dla użytkownika o ID: $userId. Treść błędu: " . json_encode($stmt->errorInfo()));
                return [];
            }

            $notesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $notes = [];
            foreach ($notesData as $noteData) {
                $notes[] = Serializer::deserialize($noteData, Note::class);
            }
            return $notes;
        }
}