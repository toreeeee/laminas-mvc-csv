<?php

namespace Person\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Person\Model\PersonRepositoryInterface;

class PersonController extends AbstractActionController
{
    private PersonRepositoryInterface $personRepository;

    function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function indexAction()
    {
        return ["persons" => $this->personRepository->getAll()];
    }
}
