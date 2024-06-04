<?php

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Person\Model\PersonTable;

class PeopleController extends AbstractActionController
{
    private $table;

    function __construct(PersonTable $table)
    {
        $this->table = $table;
    }

    public function indexAction() {
        return new ViewModel([
            "people" => $this->table->fetchAll(),
        ]);
    }
}