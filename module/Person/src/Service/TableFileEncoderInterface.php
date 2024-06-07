<?php

namespace Person\Service;

interface TableFileEncoderInterface
{
    /**
     * @param array<string> $headings
     * @param array<TableRowInterface> $rows
     * @return string
     */
    public function encode(array $headings, array $rows): string;
}
