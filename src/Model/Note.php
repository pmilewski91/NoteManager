<?php

namespace App\Model;

class Note
{
    private ?int $id = null;
    private string $title;
    private string $description;
    private string $color;
    private int $user_id; 

    public function __construct(string $title, string $description, int $userId, string $color = 'white', ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->color = $color;
        $this->user_id = $userId;
    }
    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getColor(): string
    {
        return $this->color;    
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }

    // Validations
    private function validateTitle(): bool
    {
        if (empty($this->title)) {
            throw new \InvalidArgumentException('Title cannot be empty.');
        }
        return true;
    }
    private function validateDescription(): bool
    {
        if (empty($this->description)) {
            throw new \InvalidArgumentException('Description cannot be empty.');
        }
        return true;
    }

    // Setters
    public function setTitle(string $title): void
    {
        $this->validateTitle();
        $this->title = $title;
    }
    public function setDescription(string $description): void
    {
        $this->validateDescription();
        $this->description = $description;
    }
    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}