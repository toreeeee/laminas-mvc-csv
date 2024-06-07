<?php

 namespace Person\Service\CSVFile;

 use Person\Service\CSVFile\RowValidator;
use Person\Service\TableRowInterface;

class CSVRow implements TableRowInterface
{
    /**
     * @var string[]
     */
    private array $columns;

    private array $errors = [];

    /**
     * @var array<RowValidator>
     */
    private array $validators = [];



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
    public function isError(): bool
    {
        if (count($this->errors) > 0) {
            return true;
        }

        foreach ($this->validators as $validator) {
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

    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getErrorMessage(): ?string
    {
        if (!count($this->getErrors())) {
            return null;
        }
        return implode(", ", $this->getErrors());
    }

    public function getColumnIndex(string $name): ?int
    {
        $result = array_search($name, $this->columns);
        return $result === false ? null : $result;
    }

    public function addValidator(RowValidator $validator): void
    {
        // TODO: Implement addValidator() method.
    }
}
