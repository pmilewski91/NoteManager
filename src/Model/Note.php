<?php

namespace App\Model;

use App\Service\ValidationService;

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
    /**
     * [Returns the ID of the note.]
     *
     * @return int|null
     * 
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * [Returns the title of the note.]
     *
     * @return string
     * 
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * [Returns the description of the note.]
     *
     * @return string
     * 
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * [Returns the color of the note.]
     *
     * @return string
     * 
     */
    public function getColor(): string
    {
        return $this->color;    
    }
    /**
     *  [Returns the user ID associated with the note.]
     *
     * @return int
     * 
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    // Setters
    /**
     * [Sets the title of the note after validation.]
     *
     * @param string $title
     * 
     * @return void
     * 
     */
    public function setTitle(string $title): void
    {
        ValidationService::validateNoteTitle($title);
        $this->title = $title;
    }
    /**
     * [Sets the description of the note after validation.]
     *
     * @param string $description
     * 
     * @return void
     * 
     */
    public function setDescription(string $description): void
    {
        ValidationService::validateNoteDescription($description);
        $this->description = $description;
    }
    /**
     * [Sets the color of the note.]
     *
     * @param string $color
     * 
     * @return void
     * 
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}