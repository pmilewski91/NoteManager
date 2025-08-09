<?php   

namespace App\Model;

class User
{
    private ?int $id = null;
    private string $password;
    private string $email;
    private ?string $createdAt = null;
    private ?string $updatedAt = null;


    public function __construct(string $password, string $email, ?int $id = null)
    {
        $this->id = $id;
        $this->password = $password;
        $this->email = $email;
    }
    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getEmail(): string
    {
        return $this->email;   
    }
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    // Validations
    public function validateEmail(): bool
    {
        if (empty($this->email)) {
            throw new \InvalidArgumentException('Email cannot be empty.');
        }
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('Invalid email format.');
        }
        return true;
    }
    public function validatePassword(): bool
    {
        if (empty($this->password)) {
            throw new \InvalidArgumentException('Password cannot be empty.');
        }
        if (strlen($this->password) < 4) {
            throw new \InvalidArgumentException('Password must be at least 4 characters long.');
        }
        return true;
    }


    // Setters
    public function setEmail(string $email): void
    {
        $this->validateEmail();
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->validatePassword();
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

}