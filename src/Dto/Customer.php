<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Dto;

class Customer
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Customer
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Customer
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): Customer
    {
        $this->phone = $phone;
        return $this;
    }


}
