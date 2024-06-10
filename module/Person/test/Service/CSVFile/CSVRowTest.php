<?php

namespace PersonTest\Service\CSVFile;

use Person\Service\CSVFile\CSVRow;
use PHPUnit\Framework\TestCase;

class CSVRowTest extends TestCase
{
    public function testIsValid()
    {
        $row = new CSVRow(["hello", "world"], 3);

        $this->assertFalse($row->isValid());
        $this->assertGreaterThan(0, count($row->getErrors()));
    }
}
