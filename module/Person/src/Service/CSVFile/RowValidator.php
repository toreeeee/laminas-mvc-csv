<?php

 namespace Person\Service\CSVFile;

interface RowValidator
{
    public function validate(CSVRow $row): RowValidationResult;
}
