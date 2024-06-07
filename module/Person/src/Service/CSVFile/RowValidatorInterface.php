<?php

 namespace Person\Service\CSVFile;

interface RowValidatorInterface
{
    public function validate(CSVRow $row): RowValidationResult;
}
