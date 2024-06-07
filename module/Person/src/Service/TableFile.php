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
     * @param array<string> $headings
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
}
