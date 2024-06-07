<?php

namespace Person\Model;

use Album\Model\Album;
use InvalidArgumentException;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Paginator\Adapter\LaminasDb\DbSelect;
use Laminas\Paginator\Paginator;
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

    public function getAllPaginated()
    {
        // Create a new Select object for the table:
        $select = new Select("person");

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype($this->personPrototype);

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
        // our configured select object:
            $select,
            // the adapter to run it against:
            $this->db,
            // the result set to hydrate:
            $resultSetPrototype
        );

        return new Paginator($paginatorAdapter);
    }
}
