<?php

namespace PersonTest\Service;

use Person\Service\CSVEncoder;
use Person\Service\CSVFile\CSVRow;
use Person\Service\CSVParser;
use PHPUnit\Framework\TestCase;

class CSVParserTest extends TestCase
{
    public function testDecodeCSV()
    {
        $parser = new CSVParser([]);

        $parsed = $parser->parse("first : second : third\nhello : hello : hello");
        $encoder = new CSVEncoder();

        $this->assertSame(["first", "second", "third"], $parsed->getHeadings());
        $this->assertSame(
            $encoder->encode(
                ["first", "second", "third"],
                [new CSVRow(["hello", "hello", "hello"])]
            ),
            $encoder->encode(
                ["first", "second", "third"],
                $parsed->getRows()
            )
        );
    }
}
