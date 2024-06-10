<?php

namespace PersonTest\Service;

use Person\Service\CSVEncoder;
use Person\Service\CSVFile\CSVRow;
use PHPUnit\Framework\TestCase;

class CSVEncoderTest extends TestCase
{
    private $encoder;

    protected function setUp(): void
    {
        $this->encoder = new CSVEncoder();
    }

    public function testEncode()
    {
        $encoded = $this->encoder->encode(
            ["first", "second", "third"],
            [new CSVRow(["hello", "hello", "hello"])]
        );

        $this->assertSame("first : second : third\nhello : hello : hello\n", $encoded);
    }
}
