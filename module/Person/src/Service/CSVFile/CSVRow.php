<?php

 namespace Person\Service\CSVFile;

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
     * @param int $expectedColumnsCount
     * @param array<RowValidatorInterface> $validators
     */
    public function __construct(array $columns, int $expectedColumnsCount = -1, array $validators = [])
    {
        $this->columns = $columns;
        if ($expectedColumnsCount !== -1 && count($this->columns) !== $expectedColumnsCount) {
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
            $validationResult = $validator->validate($this);

            if (!$validationResult->isOk()) {
                array_push($this->errors, ...$validationResult->getErrors());
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addColumn(string $column): void
    {
        $this->columns[] = $column;
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
