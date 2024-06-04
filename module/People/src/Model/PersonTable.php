<?php

namespace Person\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class PersonTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function deletePerson($id)
    {
        $this->tableGateway->delete(['id' => (int)$id]);
    }
}