<?php

namespace App\Repository;

use App\Database;
use App\Model\Note;
use PDO;

class NoteRepository
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    private function createNoteFromArray(array $noteData): Note {
        return new Note(
            $noteData['title'],
            $noteData['description'],
            $noteData['user_id'],
            $noteData['color'] ?? 'white',
            $noteData['id'] ?? null
        );
    }


    public function create(Note $note): void
    {
        $noteTitle = $note->getTitle();
        $noteDescription = $note->getDescription();
        $noteColor = $note->getColor();
        $noteUserId = $note->getUserId();

        $stmt = $this->db->prepare("INSERT INTO notes (title, description, color, user_id) VALUES (:title, :description, :color, :user_id)");
        $stmt->bindParam(':title', $noteTitle);
        $stmt->bindParam(':description', $noteDescription);
        $stmt->bindParam(':color', $noteColor);
        $stmt->bindParam(':user_id', $noteUserId);
        $stmt->execute();
    }

    public function update(Note $note): void
    {
        $stmt = $this->db->prepare("UPDATE notes SET title = :title, description = :description, color = :color WHERE id = :id");
        $stmt->bindParam(':title', $note->getTitle());
        $stmt->bindParam(':description', $note->getDescription());
        $stmt->bindParam(':color', $note->getColor());
        $stmt->bindParam(':id', $note->getId());
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function findById(int $id): ?Note
    {
        $stmt = $this->db->prepare("SELECT id, title, description, color, user_id, created_at FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $noteData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($noteData) {
            $this->createNoteFromArray($noteData);
            return new Note(
                $noteData['title'],
                $noteData['description'],
                $noteData['user_id'],
                $noteData['color'] ?? 'white',
                $noteData['id'] ?? null
            );
        }
        // return $stmt->fetchObject(Note::class) ?: null;
        return null;
    }

    
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, title, description, color, created_at FROM notes ORDER BY created_at DESC");
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, Note::class);
        
    }

    public function findByUserId(int $userId): array
        {
            $stmt = $this->db->prepare("SELECT id, title, description, color, user_id, created_at FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $notesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $notes = [];
            foreach ($notesData as $noteData) {
                $notes[] = $this->createNoteFromArray($noteData);
            }
            return $notes;
        }
}