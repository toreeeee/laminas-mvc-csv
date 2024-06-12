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
use Person\Service\CSVFile\CSVRow;
use Person\Service\TableFileEncoderInterface;
use Person\Service\TableFileParserInterface;

class PersonController extends AbstractActionController
{
    private PersonRepositoryInterface $personRepository;
    private PersonCommandInterface $command;
    private TableFileParserInterface $parser;
    private TableFileEncoderInterface $encoder;

    public function __construct(
        PersonRepositoryInterface $personRepository,
        PersonCommandInterface    $command,
        TableFileParserInterface  $parser,
        TableFileEncoderInterface $encoder,
    )
    {
        $this->personRepository = $personRepository;
        $this->command = $command;
        $this->parser = $parser;
        $this->encoder = $encoder;
    }

    public function indexAction()
    {
        $paginator = $this->personRepository->findAllPaginated();
        $page = (int)$this->params()->fromQuery("page", 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        $paginator->setItemCountPerPage(10);

        return ["pagination" => $paginator];
    }

    public function editAction()
    {
        $request = $this->getRequest();

        $id = $this->params()->fromRoute('id', 0);

        $person = $this->personRepository->findById($id);

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

        return $this->redirect()->toRoute('person');
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('person');
        }

        try {
            $person = $this->personRepository->findById($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('person');
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
            // TODO: add error handling support
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

        $table = $this->parser->parse($fileContent);

        $validRows = $table->getValidRows();

        $this->command->insertManyPersons(
            array_map(function ($row) {
                $columns = $row->getColumns();
                return new Person($columns[1], $columns[2], $columns[0], (float)$columns[3]);
            },
                $validRows)
        );


        $invalidRows = $table->getInvalidRows();

        if (count($invalidRows) > 0) {
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"invalid.csv\"");

            $encoded = $this->encoder->encode([...$table->getHeadings(), "errors"], $invalidRows);
            $this->response->setContent($encoded);

            return $this->response;
        }

        return ['form' => $form];
    }

    public function exportAction()
    {
        $persons = $this->personRepository->findAll();
        $encoded = $this->encoder->encode(["birthday", "first_name", "last_name", "salary"], array_map(function ($it) {
            return new CSVRow([$it["birthday"], $it["first_name"], $it["last_name"], $it["salary"]], 4);
        }, $persons->toArray()));


        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"export.csv\"");
        $this->response->setContent($encoded);

        return $this->response;
    }
}
