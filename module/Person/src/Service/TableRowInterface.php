<?php

namespace Person\Service;

use Person\Service\CSVFile\RowValidator;

interface TableRowInterface
{
    public function isError(): bool;
    public function getErrorMessage(): ?string;

    /**
     * @return array<string>
     */
    public function getColumns(): array;

    public function getColumnIndex(string $name): ?int;

    public function addValidator(RowValidator $validator): void;
}
