<?php

namespace Person\Service;

use Person\Service\CSVFile\RowValidatorInterface;

interface TableRowInterface
{
    public function isValid(): bool;
    public function getErrorMessage(): ?string;

    /**
     * @return array<string>
     */
    public function getColumns(): array;
}
