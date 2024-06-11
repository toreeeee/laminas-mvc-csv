<?php

namespace PersonTest\Model;

use Album\Model\AlbumTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\DriverInterface;
//use Laminas\Db\Adapter\Driver\Pdo;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\Adapter\Platform\PlatformInterface;
use Laminas\Db\Adapter\StatementContainerInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Platform\Platform;
use Laminas\Db\Sql\Sql;
use Laminas\ServiceManager\ServiceManager;
use Person\Model\LaminasDbSqlCommand;
use Person\Model\Person;
use phpDocumentor\Reflection\Types\This;
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
