<?php

namespace Person\Service;

interface TableRowInterface
{
    public function isValid(): bool;
    public function getErrorMessage(): ?string;

    /**
     * @return array<string>
     */
    public function getColumns(): array;
}
