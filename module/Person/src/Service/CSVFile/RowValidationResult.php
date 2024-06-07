<?php

 namespace Person\Service\CSVFile;

class RowValidationResult
{
    /**
     * @var array<string>
     */
    private array $errors;

    /**
     * @param array<string> $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function isOk(): bool
    {
        return !count($this->errors);
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
