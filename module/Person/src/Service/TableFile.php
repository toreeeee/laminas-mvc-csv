<?php

namespace Person\Service;

class TableFile
{
    /**
     * @var array<TableRowInterface>
     */
    private array $rows;

    /**
     * @var array<string>
     */
    private array $headings;

    /**
     * @param array<string>            $headings
     * @param array<TableRowInterface> $rows
     */
    public function __construct(array $headings, array $rows)
    {
        $this->headings = $headings;
        $this->rows = $rows;
    }

    /**
     * @return array<TableRowInterface>
     */
    public function getRows(): array
    {
        return $this->rows;
    }


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

            $errorMessage = implode(", ", $row->getErrors());

            // check if error message doesn't already exist on output
            if ($columns[count($columns) - 1] !== $errorMessage) {
                $row->addColumn($errorMessage);
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
}
