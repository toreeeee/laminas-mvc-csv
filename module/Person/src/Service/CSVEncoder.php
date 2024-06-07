<?php

namespace Person\Service;

use Person\Service\CSVFile\CSVRow;

class CSVEncoder implements TableFileEncoderInterface
{
    /**
     * @var array<TableRowInterface>
     */
    private array $rows;
    /**
     * @var array<string>
     */
    private array $headings;
    private string $delimiter;

    /**
     * @param array<string> $headings
     * @param array<CSVRow> $rows
     * @throws \Exception
     */
    public function __construct(array $headings, array $rows, string $delimiter = ":")
    {
        if (strlen($delimiter) !== 1) {
            throw new \Exception("Delimiter must be a single character");
        }

        $this->headings = $headings;
        $this->rows = $rows;
        $this->delimiter = $delimiter;
    }

    public function encode(): string
    {
        $text = $this->getHeader();

        foreach ($this->rows as $row) {
            $text .= implode($this->getDelimiter(), $row->getColumns()) . "\n";
        }

        return $text;
    }

    private function getHeader(): string
    {
        return implode($this->getDelimiter(), $this->headings) . "\n";
    }

    private function getDelimiter(): string
    {
        return " " . $this->delimiter . " ";
    }
}
