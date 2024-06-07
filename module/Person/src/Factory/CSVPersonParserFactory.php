<?php

namespace Person\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Person\Service\CSVFile\PersonRowValidator;
use Person\Service\CSVParser;
use Psr\Container\ContainerInterface;

class CSVPersonParserFactory implements FactoryInterface
{
    /**
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new CSVParser([new PersonRowValidator()]);
    }
}
