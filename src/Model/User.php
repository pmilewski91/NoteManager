<?php   

namespace App\Model;

use App\Service\ValidationService;

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
    /**
     * [Returns the ID of the user.]
     *
     * @return int|null
     * 
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * [Returns the password of the user.]
     *
     * @return string
     * 
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * [Returns the email of the user.]
     *
     * @return string
     * 
     */
    public function getEmail(): string
    {
        return $this->email;   
    }
    /**
     * [Returns the creation date of the user.]
     *
     * @return string|null
     * 
     */
    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
    /**
     * [Returns the last updated date of the user.]
     *
     * @return string|null
     * 
     */
    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }
    
    // Setters
    /**
     * [Sets the email of the user after validation.]
     *
     * @param string $email
     * 
     * @return void
     * 
     */
    public function setEmail(string $email): void
    {
        ValidationService::validateEmail($email);
        $this->email = $email;
    }
    /**
     * [Sets the password of the user after validation and hashes it.]
     *
     * @param string $password
     * 
     * @return void
     * 
     */
    public function setPassword(string $password): void
    {
        ValidationService::validatePassword($password);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * [Sets the creation date of the user.]
     *
     * @param string $createdAt
     * 
     * @return void
     * 
     */
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    /**
     * [Sets the last updated date of the user.]
     *
     * @param string $updatedAt
     * 
     * @return void
     * 
     */
    /**
     * [Sets the last updated date of the user.]
     *
     * @param string $updatedAt
     * 
     * @return void
     * 
     */
    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

}