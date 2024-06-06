<?php

namespace Person\Factory;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Person\Model\LaminasDbSqlRepository;
use Person\Model\Person;
use Psr\Container\ContainerInterface;

class LaminasDbSqlRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new LaminasDbSqlRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Person('', '', '', 0)
        );
    }
}
