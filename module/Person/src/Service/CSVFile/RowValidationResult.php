<?php

 namespace Person\Service\CSVFile;

class RowValidationResult
{
    /**
     * @var string[]
     */
    private array $errors;

    function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return !count($this->errors);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
