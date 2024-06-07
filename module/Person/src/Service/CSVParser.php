<?php

namespace Person\Service;

use Exception;
use Person\Service\CSVFile\RowValidatorInterface;
use RuntimeException;

class CSVParser implements TableFileParserInterface
{
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
            throw new RuntimeException("Delimiter must be exactly 1 character");
        }
        $this->delimiter = $delimiter;
    }

    public function parse(string $input): TableFile
    {
        $lines = explode("\n", $input);

        // parse first line as header
        $headings = $this->parseColumns(array_shift($lines));
        $colsPerLine = count($headings);

        return new TableFile($headings, array_map(function ($line) use ($colsPerLine) {
            $columns = $this->parseColumns($line);
            return new CSVFile\CSVRow(
                $columns,
                $colsPerLine,
                $this->row_validators
            );
        }, array_filter($lines, function ($line) {
            return !!strlen($line);
        })));
    }

    /**
     * @param string $line
     * @return array<string>
     */
    private function parseColumns(string $line): array
    {
        return array_map(function ($row) {
            return trim($row);
        }, explode($this->delimiter, $line));
    }
}
