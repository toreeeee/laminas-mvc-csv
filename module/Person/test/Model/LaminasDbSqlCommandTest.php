<?php

namespace PersonTest\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\Sql\Sql;
use Person\Model\LaminasDbSqlCommand;
use Person\Model\Person;
use PHPUnit\Framework\TestCase;

class LaminasDbSqlCommandTest extends TestCase
{
    private AdapterInterface $adapter;
    private StatementInterface $statement;

    public function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
        $this->statement = $this->createMock(StatementInterface::class);
    }

    public function testUpdatePerson()
    {
        $sql = $this->createMock(Sql::class);
        $sql->method("select")->willReturn($sql);
        $sql->method("update")->willReturn($sql);
        $sql->expects($this->once())->method("prepareStatementForSqlObject")->willReturn($this->statement);

        $result = $this->createMock(ResultInterface::class);

        $this->statement->method("execute")->with()->willReturn($result);

        $cmd = new LaminasDbSqlCommand($this->adapter, $sql);

        $person = new Person("first", "last", "none", 500, 1);

        $this->assertSame($person, $cmd->updatePerson($person));
    }

    public function testDeletePerson()
    {
        $sql = $this->createMock(Sql::class);
        $sql->method("select")->willReturn($sql);
        $sql->method("update")->willReturn($sql);
        $sql->method("delete")->willReturn($sql);
        $sql->expects($this->once())->method("prepareStatementForSqlObject")->willReturn($this->statement);

        $result = $this->createMock(ResultInterface::class);

        $this->statement->method("execute")->with()->willReturn($result);

        $cmd = new LaminasDbSqlCommand($this->adapter, $sql);

        $person = new Person("first", "last", "none", 500, 1);

        $this->assertTrue($cmd->deletePerson($person));
    }

    public function testInsertPerson()
    {
        $sql = $this->createMock(Sql::class);
        $sql->method("select")->willReturn($sql);
        $sql->method("update")->willReturn($sql);
        $sql->method("delete")->willReturn($sql);
        $sql->expects($this->once())->method("prepareStatementForSqlObject")->willReturn($this->statement);

        $result = $this->createMock(ResultInterface::class);
        $result->method("getGeneratedValue")->willReturn(1);

        $this->statement->method("execute")->with()->willReturn($result);

        $cmd = new LaminasDbSqlCommand($this->adapter, $sql);

        $person = new Person("first", "last", "none", 500, null);

        $inserted = $cmd->insertPerson($person);

        $this->assertSame(1, $inserted->getId());
    }

    public function testInsertManyPersons()
    {
        $sql = $this->createMock(Sql::class);
        $sql->method("select")->willReturn($sql);
        $sql->method("update")->willReturn($sql);
        $sql->method("delete")->willReturn($sql);
        $sql->expects($this->exactly(2))->method("prepareStatementForSqlObject")->willReturn($this->statement);

        $result = $this->createMock(ResultInterface::class);
        $result->method("getGeneratedValue")->willReturn(1);

        $this->statement->method("execute")->with()->willReturn($result);

        $cmd = new LaminasDbSqlCommand($this->adapter, $sql);

        $person = new Person("first", "last", "none", 500, null);

        $values = [$person, $person];
        $inserted = $cmd->insertManyPersons($values);

        $this->assertSame(1, $inserted[0]->getId());
        $this->assertSame(1, $inserted[1]->getId());
    }
}
