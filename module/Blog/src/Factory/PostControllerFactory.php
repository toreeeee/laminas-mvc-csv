<?php

namespace Blog\Factory;

use Blog\Controller\ListController;
use Blog\Controller\PostController;
use Blog\Model\PostRepositoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class PostControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return PostController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PostController($container->get(PostRepositoryInterface::class));
    }
}
