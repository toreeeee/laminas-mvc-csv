<?php

namespace Person\Service;

use Exception;
use Person\Service\CSVFile\CSVRow;
use Person\Service\CSVFile\RowValidationResult;
use Person\Service\CSVFile\RowValidatorInterface;

class CSVParser implements TableFileParserInterface
{
    /**
     * @var array<string>
     */
    private array $headings = [];
    private int $cols_per_line = 0;
    /**
     * @var array<CSVRow>
     */
    private array $rows = [];

    /**
     * @var array<RowValidatorInterface>
     */
    private array $row_validators = [];

    private string $delimiter;

    /**
     * @param array<RowValidatorInterface> $rowValidators
     * @param string $delimiter
     * @throws Exception
     */
    public function __construct(array $rowValidators, string $delimiter = ":")
    {
        if (strlen($delimiter) !== 1) {
            throw new Exception("Delimiter must be exactly 1 character");
        }
        $this->delimiter = $delimiter;
    }

    public function parse(string $input): void
    {
        $lines = explode("\n", $input);

        // parse first line as header
        $this->headings = $this->getColumns(array_shift($lines));
        $this->cols_per_line = count($this->headings);

        foreach ($lines as $line) {
            $columns = $this->getColumns($line);
            $this->rows[] = new CSVFile\CSVRow(
                $columns,
                $this->cols_per_line,
                $this->row_validators
            );
        }
    }

    /**
     * @param string $line
     * @return array<string>
     */
    private function getColumns(string $line): array
    {
        return array_map(function ($row) {
            return trim($row);
        }, explode($this->delimiter, $line));
    }

    public function getAmountCols(): int
    {
        return $this->cols_per_line;
    }

    /**
     * @return array<TableRowInterface>
     */
    public function getValidRows(): array
    {
        return array_filter($this->rows, function ($row) {
            return $row->isValid();
        });
    }

    /**
     * @return array<TableRowInterface>
     */
    public function getInvalidRows(): array
    {
        return array_map(function ($row) {
            $columns = $row->getColumns();
            if ($columns[count($columns) - 1] !== implode(", ", $row->getErrors())) {
                $row->addColumn(implode(", ", $row->getErrors()));
            }

            return $row;
        }, array_filter($this->rows, function ($row) {
            return !$row->isValid();
        }));
    }

    /**
     * @return array<string>
     */
    public function getHeadings(): array
    {
        return $this->headings;
    }

    /**
     * @return array<TableRowInterface>
     */
    public function getAllRows(): array
    {
        return $this->rows;
    }
}
