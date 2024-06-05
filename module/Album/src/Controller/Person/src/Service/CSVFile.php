<?php

namespace Album\Controller\Person\src\Service;

class RowValidationResult
{
    /**
     * @var string[]
     */
    private array $errors;

    public function __construct($errors)
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

interface RowValidator
{
    public function validate(\Person\Service\CSVRow $row): \Person\Service\RowValidationResult;
}

class CSVRow
{
    /**
     * @var string[]
     */
    private array $columns;

    private array $errors = [];


    public function __construct($columns, $expected_columns_count)
    {
        $this->columns = $columns;
        if (count($this->columns) !== $expected_columns_count) {
            $this->errors[] = "Amount of columns does not match header count.";
        }
    }

    /**
     * @param RowValidator[] $validators
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

class CSVFile
{
    /**
     * @var string[]
     */
    private array $headings = [];
    private int $cols_per_line = 0;
    /**
     * @var CSVRow[]
     */
    private array $rows = [];

    /**
     * @var RowValidator[]
     */
    private array $row_validators = [];

    public function addValidator(RowValidator $validator)
    {
        $this->row_validators[] = $validator;
    }

    private function getColumns($line, $delimiter = ":"): array
    {
        return explode($delimiter, $line);
    }

    public function parse(string $document)
    {
        $lines = explode("\n", $document);

        // get headings
        $this->headings = $this->getColumns(array_shift($lines));
        $this->cols_per_line = count($this->headings);

        foreach ($lines as $line) {
            $columns = $this->getColumns($line, ":");
            $this->rows[] = new CSVRow($columns, $this->cols_per_line);
        }
    }

    public function setHeadings(array $headings)
    {
        $this->headings = $headings;
    }

    public function addRow(CSVRow $row)
    {
        $this->rows[] = $row;
    }

    public function getAmountCols(): int
    {
        return $this->cols_per_line;
    }

    /**
     * @return CSVRow[]
     */
    public function getValidRows(): array
    {
        return array_filter($this->rows, function ($row) {
            return !$row->isError($this->row_validators);
        });
    }

    public function getInvalidRows(): array
    {
        return array_map(function ($row) {
            $columns = $row->getColumns();
            if ($columns[count($columns) - 1] !== implode(", ", $row->getErrors())) {
                $row->addColumn(implode(", ", $row->getErrors()));
            }

            return $row;
        }, array_filter($this->rows, function ($row) {
            return $row->isError($this->row_validators);
        }));
    }

    private function encode($rows): string
    {
        $text = implode(":", $this->headings) . "\n";

        foreach ($rows as $row) {
            $text .= implode(":", $row->get_columns()) . "\n";
        }

        return $text;
    }

    public function encodeValid(): string
    {
        return $this->encode($this->getValidRows());
    }

    public function encodeInvalid(): string
    {
        $this->headings[] = "errors";
        $result = $this->encode($this->getInvalidRows());
        array_pop($this->headings);

        return $result;
    }
}
