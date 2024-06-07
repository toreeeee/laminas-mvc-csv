<?php

namespace Person\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use RuntimeException;

class LaminasDbSqlCommand implements PersonCommandInterface
{
    private AdapterInterface $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function updatePerson(Person $person): Person
    {
        if (!$person->getId()) {
            throw new RuntimeException('Cannot update person; missing identifier');
        }

        $update = new Update('person');
        $update->set([
            'first_name' => $person->getFirstName(),
            'last_name' => $person->getLastName(),
            "birthday" => $person->getBirthday(),
            "salary" => $person->getSalary(),
        ]);
        $update->where(['id = ?' => $person->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during person update operation'
            );
        }

        return $person;
    }

    public function deletePerson(Person $person): bool
    {
        if (!$person->getId()) {
            throw new RuntimeException('Cannot delete person; missing identifier');
        }

        $delete = new Delete('person');
        $delete->where(['id = ?' => $person->getId()]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            return false;
        }

        return true;
    }

    public function insertManyPersons(array $persons): array
    {
        return array_map(function ($person) {
            return $this->insertPerson($person);
        }, $persons);
    }

    public function insertPerson(Person $person): Person
    {
        $insert = new Insert('person');
        $insert->values([
            'first_name' => $person->getFirstName(),
            'last_name' => $person->getLastName(),
            "birthday" => $person->getBirthday(),
            "salary" => $person->getSalary(),
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during person insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Person(
            $person->getFirstName(),
            $person->getLastName(),
            $person->getBirthday(),
            $person->getSalary(),
            $id
        );
    }
}
