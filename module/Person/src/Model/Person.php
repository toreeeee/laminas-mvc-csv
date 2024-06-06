<?php

namespace Person\Model;

class Person
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var string
     */
    private $first_name;
    /**
     * @var string
     */
    private $last_name;
    /**
     * @var string
     */
    private $birthday;
    /**
     * @var float
     */
    private $salary;

    public function __construct(
        string $firstName,
        string $lastName,
        string $birthday,
        float $salary,
        int | null $id = null
    ) {
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->birthday = $birthday;
        $this->salary = $salary;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
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
