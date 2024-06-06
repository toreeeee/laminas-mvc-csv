<?php

namespace Person\Model;

use InvalidArgumentException;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use RuntimeException;

class LaminasDbSqlRepository implements PersonRepositoryInterface
{
    private AdapterInterface $db;

    private HydratorInterface $hydrator;

    private Person $personPrototype;

    public function __construct(
        AdapterInterface $db,
        HydratorInterface $hydrator,
        Person $postPrototype
    ) {
        $this->db = $db;
        $this->hydrator = $hydrator;
        $this->personPrototype = $postPrototype;
    }

    public function getById(int $id): Person
    {
        $sql = new Sql($this->db);
        $select = $sql->select("person")->where(['id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Person with identifier "%s" not found.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->personPrototype);
        $resultSet->initialize($result);
        $person = $resultSet->current();

        if (!$person) {
            throw new InvalidArgumentException(sprintf(
                'Person with identifier "%s" not found.',
                $id
            ));
        }

        return $person;
    }

    public function getAll()
    {
        $sql       = new Sql($this->db);
        $select    = $sql->select('person');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->personPrototype);
        $resultSet->initialize($result);

        return $resultSet;
    }
}
