<?php

namespace Person\Service;

interface TableFileParserInterface
{
    public function parse(string $input): TableFile;
}
