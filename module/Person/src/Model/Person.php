<?php

namespace Person\Model;

class Person
{
    private int | null $id;
    private string $firstName;
    private string $lastName;
    private string $birthday;
    private float $salary;

    public function __construct(
        string $firstName,
        string $lastName,
        string $birthday,
        float $salary,
        int | null $id = null
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->salary = $salary;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getBirthday(): string
    {
        return $this->birthday;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }
}
