<?php
namespace Akobiabiolaabdbarr\ScrutinApp;

class ClassUser
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $userName;
    private string $passWord;        
    private string $role;

    public function __construct(string $username, string $password)
    {
        $this->userName = $username;
        $this->passWord = $password;
    }

    // Getters et Setters
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }


    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }


    public function getpassWord(): string
    {
        return $this->passWord;
    }

    public function setPassWord(string $passWord): self
    {
        $this->passWord = $passWord;
        return $this;
    }



    
}

?> 