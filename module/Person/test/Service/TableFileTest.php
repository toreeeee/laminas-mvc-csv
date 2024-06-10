<?php

namespace PersonTest\Service;

use Person\Service\CSVFile\CSVRow;
use Person\Service\TableFile;
use PHPUnit\Framework\TestCase;

class TableFileTest extends TestCase
{
    public function testGetRows()
    {
        $rows = [new CSVRow(["first", "second", "third"])];

        $file = new TableFile([], $rows);

        $this->assertSame($rows, $file->getRows());
    }

    public function testGetHeadings()
    {
        $headings = ["hello", "world", "third"];

        $file = new TableFile($headings, []);

        $this->assertSame($headings, $file->getHeadings());
    }
}
