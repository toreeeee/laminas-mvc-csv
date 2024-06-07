<?php

namespace Person\Service;

use Person\Service\CSVFile\CSVRow;

class CSVEncoder implements TableFileEncoderInterface
{
    /**
     * @var TableRowInterface[]
     */
    private array $rows;
    /**
     * @var string[]
     */
    private array $headings;

    /**
     * @param array<string> $headings
     * @param array<CSVRow> $rows
     */
    public function __construct(array $headings, array $rows)
    {
        $this->headings = $headings;
        $this->rows = $rows;
    }

    private function getHeader()
    {
        return implode(":", $this->headings) . "\r\n";
    }

    public function encode(): string
    {
        $text = $this->getHeader();

        foreach ($this->rows as $row) {
            $text .= implode(":", $row->getColumns()) . "\n";
        }

        return $text;
    }
}
