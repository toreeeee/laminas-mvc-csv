<?php

namespace Album\Controller\Person\src\Controller;

use Album\Controller\Person\src\Form\PersonUploadForm;
use Album\Controller\Person\src\Model\PersonTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class PersonController extends AbstractActionController
{
    private $table;

    public function __construct(PersonTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form = new PersonUploadForm();
        $form->get("submit")->setValue("Upload");

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($post);

        if (!$form->isValid()) {
            return ['form' => $form];
        }

//        header("Content-Type", "text/csv");
//        header('Content-Description: File Transfer');
//        header('Pragma: public');
//        header('Content-Transfer-Encoding: binary');
//        header('Content-Disposition: attachment; filename=invalid.csv');
//
//        echo "geburtstag:vorname:nachname:gehalt\n";


       // print_r($form->getData());

        return $this->response;

        //return $this->response;
    }

    public function uploadAction()
    {
        return new ViewModel([]);
    }
}