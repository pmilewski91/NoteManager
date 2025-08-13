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

    /**
     * [Checks if a user exists in the database by email.]
     *
     * @param string $email
     * 
     * @return bool
     * 
     */
    public function checkIfUserExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }
    /**
     * [Registers a new user in the database.]
     *
     * @param string $email
     * @param string $password
     * 
     * @return User
     * 
     */
    public function register(string $email, string $password): User
    {
        $user = new User($password, $email);
        $this->userRepository->create($user);
        return $user;
    }

    /**
     * [Logs in a user by checking the email and password against the database.]
     *
     * @param string $email
     * @param string $password
     * 
     * @return User|null
     * 
     */
    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ];
            return $user;
        }
        return null;
    }
    /**
     * [Logs out the user by unsetting the session variable.]
     *
     * @return void
     * 
     */
    public static function logout(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        header('Location: index.php');
        exit();
    }

    /**
     * [Checks if the user is logged in by verifying session variables.]
     *
     * @return bool
     * 
     */
    public static function checkIfLogging(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user'] && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']) && isset($_SESSION['user']['email']) && !empty($_SESSION['user']['email']) && isset($_SESSION['user']['password']) && !empty($_SESSION['user']['password']));
    }
}
