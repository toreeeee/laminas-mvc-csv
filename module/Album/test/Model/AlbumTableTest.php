<?php

namespace AlbumTest\Model;

use Album\Model\AlbumTable;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use PHPUnit\Framework\TestCase;

class AlbumTableTest extends TestCase
{
    private $tableGateway;
    private $albumTable;

    protected function setUp(): void
    {
        $this->tableGateway = $this->createMock(TableGatewayInterface::class);
        $this->albumTable = new AlbumTable($this->tableGateway);
    }

    public function testFetchAllReturnsAllAlbums(): void
    {
        $resultSet = $this->createMock(ResultSetInterface::class);
        $this->tableGateway->expects($this->once())
            ->method('select')
            ->willReturn($resultSet);

        $this->assertSame($resultSet, $this->albumTable->fetchAll());
    }
}