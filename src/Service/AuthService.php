<?php

namespace App\Service;
use App\Repository\UserRepository;
use App\Model\User;
use App\Database;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(Database $db)
    {
        $this->userRepository = new UserRepository($db);
    }
    
    public function checkIfUserExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }
    public function register(string $email, string $password): User
    {
        $user = new User($password, $email);
        $this->userRepository->create($user);
        return $user;
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }
    public function logout(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }
}