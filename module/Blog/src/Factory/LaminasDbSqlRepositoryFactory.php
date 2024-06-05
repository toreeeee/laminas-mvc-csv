<?php

namespace Blog\Factory;

use Blog\Model\LaminasDbSqlRepository;
use Blog\Model\Post;
use Interop\Container\Containerinterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LaminasDbSqlRepositoryFactory implements FactoryInterface
{
    public function __invoke(Containerinterface $container, $requestedName, array $options = null)
    {
        return new LaminasDbSqlRepository(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Post('', '')
        );
    }
}
