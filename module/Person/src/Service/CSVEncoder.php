<?php

namespace Person\Service;

use Person\Service\CSVFile\CSVRow;
use RuntimeException;

class CSVEncoder implements TableFileEncoderInterface
{
    private string $delimiter;

    /**
     * @param array<string> $headings
     * @param array<CSVRow> $rows
     * @throws \Exception
     */
    public function __construct(string $delimiter = ":")
    {
        if (strlen($delimiter) !== 1) {
            throw new \RuntimeException("Delimiter must be a single character");
        }

        $this->delimiter = $delimiter;
    }

    public function encode(array $headings, array $rows): string
    {
        // add headings
        $text = implode($this->getDelimiter(), $headings) . "\n";

        foreach ($rows as $row) {
            if (count($row->getColumns()) !== count($headings)) {
                throw new RuntimeException("Headings size doesn't match row size");
            }
            $text .= implode($this->getDelimiter(), $row->getColumns()) . "\n";
        }

        return $text;
    }


    private function getDelimiter(): string
    {
        return " " . $this->delimiter . " ";
    }
}
