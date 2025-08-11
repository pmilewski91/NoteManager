<?php

namespace App\Service;

class ValidationService
{
    /**
     * [Validates the email format and checks if it is not empty.]
     *
     * @param string $email
     * 
     * @return bool
     * 
     */
    public static function validateEmail(string $email): bool
    {
        if (empty($email)) {
            throw new \InvalidArgumentException('Email cannot be empty.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format.');
        }
        return true;
    }

    /**
     * [Validates the password length and checks if it is not empty.]
     *
     * @param string $password
     * 
     * @return bool
     * 
     */
    public static function validatePassword(string $password): bool
    {
        if (empty($password)) {
            throw new \InvalidArgumentException('Password cannot be empty.');
        }
        if (strlen($password) < 4) {
            throw new \InvalidArgumentException('Password must be at least 4 characters long.');
        }
        return true;
    }

    /**
     * [Validates the note title and checks if it is not empty.]
     *
     * @param string $title
     * 
     * @return bool
     * 
     */
    public static function validateNoteTitle(string $title): bool
    {
        if (empty($title)) {
            throw new \InvalidArgumentException('Title cannot be empty.');
        }

        return true;
    }

    /**
     * [Validates the note description and checks if it is not empty.]
     *
     * @param string $description
     * 
     * @return bool
     * 
     */
    public static function validateNoteDescription(string $description): bool
    {
        if (empty($description)) {
            throw new \InvalidArgumentException('Description cannot be empty.');
        }

        return true;
    }

    /**
     * [Validates the user ID and checks if it is a positive integer.]
     *
     * @param int $userId
     * 
     * @return bool
     * 
     */
    public static function validateUserId(int $userId): bool
    {
        if ($userId <= 0 || !is_int($userId)) {
            throw new \InvalidArgumentException('Invalid user ID.');
        }
        
        return true;
    }
}