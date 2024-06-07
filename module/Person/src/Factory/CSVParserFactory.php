<?php

namespace Person\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Person\Service\CSVParser;
use Psr\Container\ContainerInterface;

class CSVParserFactory implements FactoryInterface
{
    /**
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new CSVParser([]);
    }
}
