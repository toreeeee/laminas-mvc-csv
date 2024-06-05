<?php

namespace Blog\Controller;

use Blog\Model\PostRepositoryInterface;
use Laminas\Mvc\Controller\AbstractActionController;

class ListController extends AbstractActionController
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }
}
