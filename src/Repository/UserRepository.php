<?php

namespace App\Repository;

use App\Database;
use App\Model\User;
use PDO;
use App\Service\Serializer;
use App\Service\ValidationService;    

class UserRepository
{
    private object $db;
    private string $userPassword;
    private string $userEmail;
    private int $userId;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }


    /**
     * [Extracts data from the User object and sets it to the class properties.]
     *
     * @param User $user
     * 
     * @return void
     * 
     */
    private function getAndSetUserData(User $user): void
    {
        ValidationService::validateEmail($user->getEmail());
        ValidationService::validatePassword($user->getPassword());
        $this->userPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $this->userEmail = $user->getEmail();
        $this->userId = $user->getId();
    }

    /**
     * [Creates a new user in the database.]
     *
     * @param User $user
     * 
     * @return bool
     * 
     */
    public function create(User $user): bool
    {
        $this->getAndSetUserData($user);

        $stmt = $this->db->prepare("INSERT INTO users (password, email) VALUES (:password, :email)");
        $stmt->bindParam(':password', $this->userPassword);
        $stmt->bindParam(':email', $this->userEmail);
        $success = $stmt->execute();
        if (!$success) {
            throw new \Exception('Failed to create user.');
        }
        return $success;
    }

    /**
     * [Updates an existing user in the database.]
     *
     * @param User $user
     * 
     * @return bool
     * 
     */
    public function update(User $user): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password, email = :email WHERE id = :id");
        $stmt->bindParam(':password', $this->userPassword);
        $stmt->bindParam(':email', $this->userEmail);
        $stmt->bindParam(':id', $this->userId);
        $success = $stmt->execute();
        if (!$success) {
            throw new \Exception('Failed to update user.');
        }
        return $success;
    }

    /**
     * [Deletes a user from the database.]
     *
     * @param int $id
     * 
     * @return bool
     * 
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $success = $stmt->execute();

        if (!$success) {
            throw new \Exception('Failed to delete user.');
        }

        return $success;
    }

    /**
     * [Finds a user by ID in the database.]
     *
     * @param int $id
     * 
     * @return User|null
     * 
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT id, password, email FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $success = $stmt->execute();

        if (!$success) {
            throw new \Exception('Failed to find user by ID.');
        }
        
        return $stmt->fetchObject(User::class) ?: null;
    }

    /**
     * [Finds a user by email in the database.]
     *
     * @param string $email
     * 
     * @return User|null
     * 
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $success = $stmt->execute();

        if (!$success) {
            throw new \Exception(json_encode($stmt->errorInfo()));
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData === false) {
            return null;
        }

        return Serializer::deserialize($userData, User::class);
    }

    /**
     * [Finds all users in the database and returns them as an array of User objects.]
     *
     * @return array
     * 
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, password, email FROM users ORDER BY created_at DESC");
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }
}