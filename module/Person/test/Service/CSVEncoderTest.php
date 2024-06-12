<?php

namespace PersonTest\Service;

use Person\Service\CSVEncoder;
use Person\Service\CSVFile\CSVRow;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CSVEncoderTest extends TestCase
{
    private CSVEncoder $encoder;

    public function testEncodeWithValidInput(): void
    {
        $encoded = $this->encoder->encode(
            ["first", "second", "third"],
            [new CSVRow(["hello", "hello", "hello"])]
        );

        $this->assertSame("first : second : third\nhello : hello : hello\n", $encoded);
    }

    public function testEncodeWithMismatchOfColumnAndHeadingCountShouldThrow(): void
    {
        $this->expectException(RuntimeException::class);

        $this->encoder->encode(
            ["first", "second", "third"],
            [new CSVRow(["hello", "hello", "hello", "hello"])]
        );
    }

    protected function setUp(): void
    {
        $this->encoder = new CSVEncoder();
    }
}
