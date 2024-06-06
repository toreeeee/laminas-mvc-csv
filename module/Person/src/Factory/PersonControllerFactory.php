<?php

namespace Person\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Person\Controller\PersonController;
use Person\Model\PersonCommandInterface;
use Person\Model\PersonRepositoryInterface;
use Psr\Container\ContainerInterface;

class PersonControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new PersonController($container->get(PersonRepositoryInterface::class), $container->get(PersonCommandInterface::class));
    }
}
