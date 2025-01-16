<?php

namespace PH7\Learnphp\Entity;

class User
{
    private string $userUuid;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;
    private string $password;

    private string $createdDate;

    public function setUserUuid(string $userUuid): self
    {
        $this->userUuid = $userUuid;
        return $this;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function setFirstName(string $firstName): self{
        $this->firstName = $firstName;
        return $this;

    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): self{
        $this->lastName = $lastName;
        return $this;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPhone(string $phone): self{
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPassword(string $password): self{
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


        public function setCreatedDate(string $date) :self{
        $this->createdDate = $date;
        return $this;
        }

        public function getCreatedDate(): string{
        return $this->createdDate;
        }
}