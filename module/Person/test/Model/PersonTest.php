<?php

namespace PersonTest\Model;

use Album\Model\Album;
use Person\Model\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $person = new Person();
        $data = [
            "first_name" => "First",
            "last_name" => "Last",
            "id" => 123,
            "birthday" => "01.0.1.2024",
            "salary" => 500.0
        ];

        $person->exchangeArray($data);

        $this->assertSame(
            $data['first_name'],
            $person->getFirstName(),
            '"first_name" was not set correctly'
        );

        $this->assertSame(
            $data['last_name'],
            $person->getLastName(),
            '"last_name" was not set correctly'
        );

        $this->assertSame(
            $data['id'],
            $person->getId(),
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['birthday'],
            $person->getBirthday(),
            '"birthday" was not set correctly'
        );

        $this->assertSame(
            $data['salary'],
            $person->getSalary(),
            '"salary" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $person = new Person();

        $person->exchangeArray([
            'first_name' => 'some artist',
            'id' => 123,
            'birthday' => '01.01.2024',
        ]);
        $person->exchangeArray([]);

        $this->assertNull($person->getLastName(), '"artist" should default to null');
        $this->assertNull($person->getSalary(), '"id" should default to null');
    }

    public function testGetFullNameFormatter()
    {
        $person = new Person("First", "Last");

        $this->assertSame("First Last", $person->getFullName());
    }

    public function testGetSalaryFormatter()
    {
        $person = new Person(null, null, null, 500.0);

        $this->assertSame("500,00 €", $person->getFormattedSalary());
    }

    public function testGetBirthdayFormatter()
    {
        $person = new Person(null, null, "01.01.2024");
        $this->assertSame("01.01.2024", $person->getFormattedBirthday());

        $person = new Person(null, null, "2024-01-01");
        $this->assertSame("01.01.2024", $person->getFormattedBirthday());
    }
}
