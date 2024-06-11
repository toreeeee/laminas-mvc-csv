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
    public function testUpdatePerson()
    {
        $select = $this->createMock(Sql::class);

        $mockDbAdapter = $this->createMock(AdapterInterface::class);
        $statement = $this->createMock(StatementInterface::class);

        $sql = $this->createMock(Sql::class);
        $sql->method("select")->willReturn($select);
        $sql->method("update")->willReturn($statement);
        $sql->method("prepareStatementForSqlObject")->willReturn($statement);

        $result = $this->createMock(ResultInterface::class);

        $statement->method("execute")->with()->willReturn($result);

        $sql = new LaminasDbSqlCommand($mockDbAdapter, $sql);

        $person = new Person("first", "last", "none", 500, 1);

        $this->assertSame($person, $sql->updatePerson($person));
    }


//    private AdapterInterface $adapter;
//
//    private $command;
//
//    protected function setUp(): void
//    {
//        $this->adapter = $this->createMock(AdapterInterface::class);
//        $platform = $this->createMock(PlatformInterface::class);
//
//        $platform->method("getName")->willReturn("SQLite");
//
//        $this->adapter->platform = $platform;
//
////        $this->adapter->platform = $platform;
//
//        $this->command = new LaminasDbSqlCommand($this->adapter);
//    }
//
//    public function testUpdatePerson()
//    {
//        $resultSet = $this->createMock(ResultSetInterface::class);
//
////        $this->statement->expects($this->once())
////            ->method("execute")
////            ->willReturn($resultSet);
//
//        $driver = $this->createMock(DriverInterface::class);
//        $platform = $this->createMock(Platform::class);
//
//
//        $this->adapter->method("getDriver")->willReturn($driver);
//
//        $statement = $this->createMock(StatementInterface::class);
//
//        $driver->expects($this->once())
//            ->method('createStatement')
//            ->willReturn($statement);
//
//
//        $platform = $this->createMock(PlatformInterface::class);
//        $platform->method("getName")->willReturn("SQLite");
//
////        $statement->method("setObject")->willReturn($platform);
//
////        $driver->
//
////        $this->driver
////            ->expects($this->once())
////            ->method("createStatement")
////            ->willReturn($this->statement);
////
////        $this->platform->expects($this->once())
////            ->method("prepareStatement")
////            ->willReturn($this->statement);
//
////        $this->adapter
////            ->expects($this->once())
////            ->method("getName")
////            ->willReturn("Person");
//
//        $this->command->updatePerson(
//            new Person("First", "Last", "01.01.2024", 500.0, 1)
//        );
//    }
}
