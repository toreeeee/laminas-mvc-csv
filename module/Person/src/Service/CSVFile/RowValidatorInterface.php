<?php

 namespace Person\Service\CSVFile;

use Person\Service\TableRowInterface;

interface RowValidatorInterface
{
    /**
     * @param CSVRow $row
     * @return RowValidationResult
     */
    public function validate(TableRowInterface $row): RowValidationResult;
}
