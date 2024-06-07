<?php

namespace Person\Service;

use Person\Service\CSVFile\CSVRow;
use Person\Service\CSVFile\RowValidationResult;

class CSVParser implements TableFileParserInterface
{
    /**
     * @var string[]
     */
    private array $headings = [];
    private int $cols_per_line = 0;
    /**
     * @var \Person\Service\CSVFile\CSVRow[]
     */
    private array $rows = [];

    /**
     * @var \Person\Service\CSVFile\RowValidator[]
     */
    private array $row_validators = [];

    public function addValidator(CSVFile\RowValidator $validator)
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
            $this->rows[] = new CSVFile\CSVRow($columns, $this->cols_per_line);
        }
    }

    public function setHeadings(array $headings)
    {
        $this->headings = $headings;
    }

    public function addRow(CSVFile\CSVRow $row)
    {
        $this->rows[] = $row;
    }

    public function getAmountCols(): int
    {
        return $this->cols_per_line;
    }

    /**
     * @return \Person\Service\CSVFile\CSVRow[]
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

    public function encode($valid)
    {
        return $valid ? $this->itnlEncode($this->getValidRows()) : $this->itnlEncode($this->getInvalidRows());
    }

    /**
     * @param array<CSVRow> $rows
     * @return string
     */
    private function itnlEncode(array $rows): string
    {
        $text = implode(":", $this->headings) . "\n";

        foreach ($rows as $row) {
            $text .= implode(":", $row->getColumns()) . "\n";
        }

        return $text;
    }

    public function encodeValid(): string
    {
        return $this->itnlEncode($this->getValidRows());
    }

    public function encodeInvalid(): string
    {
        $this->headings[] = "errors";
        $result = $this->itnlEncode($this->getInvalidRows());
        array_pop($this->headings);

        return $result;
    }

    public function getHeadings(): array
    {
        return $this->headings;
    }
}
