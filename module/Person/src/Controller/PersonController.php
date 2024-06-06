<?php

namespace Person\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Person\Form\PersonForm;
use Person\Model\Person;
use Person\Model\PersonCommandInterface;
use Person\Model\PersonRepositoryInterface;

class PersonController extends AbstractActionController
{
    private PersonRepositoryInterface $personRepository;

    private PersonCommandInterface $command;

    function __construct(PersonRepositoryInterface $personRepository, PersonCommandInterface $command)
    {
        $this->personRepository = $personRepository;
        $this->command = $command;
    }

    public function indexAction()
    {
        return ["persons" => $this->personRepository->getAll()];
    }

    public function editAction()
    {
        return [];
    }

    public function deleteAction()
    {
        $form = new PersonForm();

        return ["form" => $form];
    }

    public function addAction()
    {
        $form = new PersonForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $data = $form->getData();

        try {
            $post = $this->command->insertPerson($data);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this->redirect()->toRoute('person');
    }
}
