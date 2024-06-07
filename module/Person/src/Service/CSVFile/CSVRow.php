<?php

 namespace Person\Service\CSVFile;

 use Person\Service\CSVFile\RowValidatorInterface;
use Person\Service\TableRowInterface;

class CSVRow implements TableRowInterface
{
    /**
     * @var array<string>
     */
    private array $columns;

    /**
     * @var array<string>
     */
    private array $errors = [];

    /**
     * @var array<RowValidatorInterface>
     */
    private array $validators = [];

    /**
     * @param array<string> $columns
     * @param int $expected_columns_count
     * @param array<RowValidatorInterface> $validators
     */
    public function __construct(array $columns, int $expected_columns_count, array $validators = [])
    {
        $this->columns = $columns;
        if (count($this->columns) !== $expected_columns_count) {
            $this->errors[] = "Amount of columns does not match header count.";
        }
        $this->validators = $validators;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (count($this->errors) > 0) {
            return false;
        }

        foreach ($this->validators as $validator) {
            $validation_result = $validator->validate($this);

            if (!$validation_result->isOk()) {
                array_push($this->errors, ...$validation_result->getErrors());
                return false;
            }
        }

        return true;
    }

    public function addColumn(string $column): void
    {
        $this->columns[] = $column;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array<string>
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
}
