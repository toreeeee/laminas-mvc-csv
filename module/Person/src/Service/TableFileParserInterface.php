<?php

namespace Person\Service;

interface TableFileParserInterface
{
    public function parse(string $input);

    /**
     * @return array<TableRowInterface>
     */
    public function getAllRows(): array;

    /**
     * @return array<TableRowInterface>
     */
    public function getValidRows(): array;

    /**
     * @return array<TableRowInterface>
     */
    public function getInvalidRows(): array;

    /**
     * @return array<string>
     */
    public function getHeadings(): array;
}
