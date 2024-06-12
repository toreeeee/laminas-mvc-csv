<?php

namespace PersonTest\Service\CSVFile;

use Person\Service\CSVFile\CSVRow;
use PHPUnit\Framework\TestCase;

class CSVRowTest extends TestCase
{
    public function testIsValid()
    {
        $row = new CSVRow(["hello", "world"], 2);

        $this->assertTrue($row->isValid());
        $this->assertSame(0, count($row->getErrors()));
    }

    public function testIsInvalid()
    {
        $row = new CSVRow(["hello", "world"], 3);

        $this->assertFalse($row->isValid());
        $this->assertGreaterThan(0, count($row->getErrors()));
    }
    
    public function testGetErrors()
    {
        $row = new CSVRow(["hello", "world"], 3);

        $this->assertGreaterThan(0, count($row->getErrors()));
    }

    public function testAddColumn()
    {
        $row = new CSVRow(["hello", "world"]);

        $this->assertSame(2, count($row->getColumns()));
        $row->addColumn("new col");
        $this->assertSame(3, count($row->getColumns()));
        $cols = $row->getColumns();
        $this->assertSame("new col", end($cols));
    }

    public function testGetColumns()
    {
        $cols = ["hello", "world", "bla", "bli"];
        $row = new CSVRow($cols);

        $this->assertSame($cols, $row->getColumns());
    }
}
