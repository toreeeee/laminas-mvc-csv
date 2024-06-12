<?php

namespace PersonTest\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\StatementInterface;
use PHPUnit\Framework\TestCase;

class LaminasDbSqlRepositoryTest extends TestCase
{
    private AdapterInterface $adapter;
    private StatementInterface $statement;

//    public function setUp(): void
//    {
//        $this->adapter = $this->createMock(AdapterInterface::class);
//        $this->statement = $this->createMock(StatementInterface::class);
//    }

//    public function testGetById()
//    {
//        $sql = $this->createMock(Sql::class);
//        $sql->method("update")->willReturn($sql);
//        $select = $this->createMock(Select::class);
//        $sql->method("select")->willReturn($select);
//        $select->method("where")->willReturn($select);
//        $sql->expects($this->once())->method("prepareStatementForSqlObject")->willReturn($this->statement);
//
//
//        $person = new Person("first", "last", "none", 500, 1);
//
//
//        $result = $this->createMock(ResultInterface::class);
//        $result->method("isQueryResult")->willReturn(true);
//        $result->method("getFieldCount")->willReturn(1);
//        $result->method("isBuffered")->willReturn(false);
//
//        $this->statement->method("execute")->with()->willReturn($result);
//
//        $hydrator = $this->createMock(ReflectionHydrator::class);
//
//
//        $cmd = new LaminasDbSqlRepository(
//            $this->adapter,
//            $hydrator,
//            new Person('', '', '', 0),
//            $sql
//        );
//
//        $person = new Person("first", "last", "none", 500, 1);
//
//        $this->assertSame($person, $cmd->getById(1));
//    }

//    public function testGetAll()
//    {
//        //
//    }
//
//    public function testGetAllPaginated()
//    {
//    }
}
