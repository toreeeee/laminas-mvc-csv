<?php

namespace PersonTest\Service;

use Person\Service\CSVFile\CSVRow;
use Person\Service\TableFile;
use PHPUnit\Framework\TestCase;

class TableFileTest extends TestCase
{
    public function testEmpty()
    {
        $file = new TableFile([], []);

        $this->assertSame([], $file->getRows());
        $this->assertSame([], $file->getHeadings());
        $this->assertSame([], $file->getInvalidRows());
        $this->assertSame([], $file->getValidRows());
    }

    public function testGetRows()
    {
        $rows = [
            new CSVRow(["first", "second", "third"]),
            new CSVRow(["4", "5", "6"]),
        ];

        $file = new TableFile([], $rows);

        $this->assertSame($rows, $file->getRows());
    }

    public function testGetHeadings()
    {
        $headings = ["hello", "world", "third"];

        $file = new TableFile($headings, []);

        $this->assertSame($headings, $file->getHeadings());
    }

    public function testReturnsSameAmountAsInput()
    {
        $inputRows = [
            new CSVRow(["test column"], 2),
            new CSVRow(["test column"], 1),
            new CSVRow(["test column", "test", "value"], 3),
            new CSVRow(["test column", "v"], 1),
        ];

        $table = new TableFile(
            [],
            $inputRows
        );

        $this->assertSame($inputRows, $table->getRows());
        $this->assertSame(count($inputRows), count($table->getInvalidRows()) + count($table->getValidRows()));
    }

    public function testGetValidRows()
    {
        $row = new CSVRow(["test", "column"], 2);

        $table = new TableFile(
            [],
            [
                new CSVRow(["test column"], 2),
                $row,
            ]
        );

        $this->assertContains($row, $table->getValidRows());
        $this->assertSame(1, count($table->getValidRows()));
    }

    public function testGetInvalidRows()
    {
        $row = new CSVRow(["test", "column"], 3);

        $table = new TableFile(
            [],
            [
                new CSVRow(["test column"], 1),
                $row,
            ]
        );

        $this->assertContains($row, $table->getInvalidRows());
        $this->assertSame(1, count($table->getInvalidRows()));
    }
}
