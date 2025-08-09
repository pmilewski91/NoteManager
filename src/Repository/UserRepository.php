<?php

namespace App\Repository;

use App\Database;
use App\Model\User;
use PDO;    

class UserRepository
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    private function createUserFromArray(array $userData): User {
        return new User(
            $userData['password'],
            $userData['email'],
            $userData['id']
        );
    }

    public function create(User $user): void
    {
        $userPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $userEmail = $user->getEmail();

        $stmt = $this->db->prepare("INSERT INTO users (password, email) VALUES (:password, :email)");
        $stmt->bindParam(':password', $userPassword);
        $stmt->bindParam(':email', $userEmail);
        $stmt->execute();
    }

    public function update(User $user): void
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password, email = :email WHERE id = :id");
        $stmt->bindParam(':password', $user->getPassword());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':id', $user->getId());
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT id, password, email FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetchObject(User::class) ?: null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData) {
            return $this->createUserFromArray($userData);
        }

        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, password, email FROM users ORDER BY created_at DESC");
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }
}