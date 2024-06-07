<?php

namespace Person\Model;

interface PersonCommandInterface
{
    /**
     * @param Person $person The post to insert; may or may not have an identifier.
     * @return Person The inserted post, with identifier.
     */
    public function insertPerson(Person $person): Person;

    /**
     * @param array<Person> $persons
     * @return array<Person>
     */
    public function insertManyPersons(array $persons): array;

    /**
     * @param Person $person The post to update; must have an identifier.
     * @return Person The updated post.
     */
    public function updatePerson(Person $person): Person;

    /**
     * @param Person $person The post to delete.
     * @return bool
     */
    public function deletePerson(Person $person): bool;
}
