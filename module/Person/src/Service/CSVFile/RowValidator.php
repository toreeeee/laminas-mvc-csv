<?php

 namespace Person\Service\CSVFile;

use Person\Service\CSVFile\CSVRow;

interface RowValidator
{
    public function validate(CSVRow $row): RowValidationResult;
}
