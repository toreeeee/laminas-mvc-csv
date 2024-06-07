<?php

namespace Person\Controller;

use InvalidArgumentException;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Person\Form\ImportPersonForm;
use Person\Form\PersonForm;
use Person\Model\Person;
use Person\Model\PersonCommandInterface;
use Person\Model\PersonRepositoryInterface;
use Person\Service\CSVEncoder;
use Person\Service\CSVFile\CSVRow;
use Person\Service\CSVParser;

use function Amp\Iterator\toArray;

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
        $request = $this->getRequest();

        $id = $this->params()->fromRoute('id', 0);

        $person = $this->personRepository->getById($id);

        $form = new PersonForm();
        $form->bind($person);
        $form->get("submit")->setAttribute("value", "Save");

        if (!$request->isPost()) {
            return ["form" => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $this->command->updatePerson($form->getData());

        return $this->redirect()->toRoute('person',);
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('person');
        }

        try {
            $person = $this->personRepository->getById($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['person' => $person]);
        }

        if (
            $id != $request->getPost('id')
            || 'Delete' !== $request->getPost('confirm', 'no')
        ) {
            return $this->redirect()->toRoute('person');
        }

        $person = $this->command->deletePerson($person);
        return $this->redirect()->toRoute('person');
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

    public function importAction()
    {
        $form = new ImportPersonForm();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ["form" => $form]; // TODO: return form here
        }

        $form->setData(array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        ));

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $fileContent = file_get_contents($form->getData()["file"]["tmp_name"]);

        try {
            $parser = new CSVParser([]);
        } catch (\Exception $e) {
            // TODO: handle exception
            throw $e;
        }
        $parser->parse($fileContent);

        $validRows = $parser->getValidRows();

        $this->command->insertManyPersons(
            array_map(function ($row) {
                    $columns = $row->getColumns();
                    return new Person($columns[1], $columns[2], $columns[0], (float)$columns[3]);
            },
                $validRows)
        );


        try {
            $encoder = new CSVEncoder($parser->getHeadings(), $parser->getInvalidRows());
        } catch (\Exception $e) {
            // TODO: handle exception
            throw $e;
        }

        print_r($encoder->encode());
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"invalid.csv\"");
        die;


        return ["form" => $form];
    }
}
