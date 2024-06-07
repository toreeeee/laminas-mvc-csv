<?php

namespace Person\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Person\Service\CSVEncoder;
use Psr\Container\ContainerInterface;

class CSVEncoderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new CSVEncoder();
    }
}
