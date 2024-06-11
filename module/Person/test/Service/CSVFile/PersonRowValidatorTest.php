<?php

namespace PersonTest\Service\CSVFile;

use Person\Service\CSVFile\CSVRow;
use Person\Service\CSVFile\PersonRowValidator;
use Person\Service\CSVFile\RowValidationResult;
use Person\Service\CSVFile\RowValidatorInterface;
use PHPUnit\Framework\TestCase;

class PersonRowValidatorTest extends TestCase
{
    private PersonRowValidator $validator;

    public function setUp(): void
    {
        $this->validator = new PersonRowValidator();
    }

    public function testValidationValid()
    {
        $this->assertInstanceOf(RowValidatorInterface::class, $this->validator);

        $validResult = $this->validator->validate(new CSVRow(["2001-08-21", "Edgar", "Nentzl", 500]));

        $this->assertInstanceOf(RowValidationResult::class, $validResult);
        $this->assertTrue($validResult->isOk());
        $this->assertSame(0, count($validResult->getErrors()));
    }

    public function testValidationInvalid()
    {
        $this->assertInstanceOf(RowValidatorInterface::class, $this->validator);

        $invalidResult = $this->validator->validate(new CSVRow(["2001-08-32", "Edgar", "Nentzl", 500]));

        $this->assertInstanceOf(RowValidationResult::class, $invalidResult);
        $this->assertFalse($invalidResult->isOk());
        $this->assertGreaterThan(0, count($invalidResult->getErrors()));
    }
}
