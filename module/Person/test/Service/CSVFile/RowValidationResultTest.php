<?php

namespace PersonTest\Service\CSVFile;

use Person\Service\CSVFile\RowValidationResult;
use PHPUnit\Framework\TestCase;

class RowValidationResultTest extends TestCase
{
    public function testRowValidationResultOk()
    {
        $validResult = new RowValidationResult([]);

        $this->assertTrue($validResult->isOk());
    }

    public function testRowValidationResultNotOk()
    {
        $invalidResult = new RowValidationResult(["some error"]);

        $this->assertFalse($invalidResult->isOk());
        $this->assertSame(["some error"], $invalidResult->getErrors());
    }
}
