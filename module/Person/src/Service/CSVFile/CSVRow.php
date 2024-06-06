<?php

 namespace Person\Service\CSVFile;

use Person\Service\RowValidator;

class CSVRow
{
    /**
     * @var string[]
     */
    private array $columns;

    private array $errors = [];


    function __construct($columns, $expected_columns_count)
    {
        $this->columns = $columns;
        if (count($this->columns) !== $expected_columns_count) {
            $this->errors[] = "Amount of columns does not match header count.";
        }
    }

    /**
     * @param \Person\Service\CSVFile\RowValidator[] $validators
     * @return bool
     */
    public function isError(array $validators): bool
    {
        if (count($this->errors) > 0) {
            return true;
        }

        foreach ($validators as $validator) {
            $validation_result = $validator->validate($this);

            if (!$validation_result->isValid()) {
                array_push($this->errors, ...$validation_result->getErrors());
                return true;
            }
        }

        return false;
    }

    public function addColumn($column)
    {
        $this->columns[] = $column;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
