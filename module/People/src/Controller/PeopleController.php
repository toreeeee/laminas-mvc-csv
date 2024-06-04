<?php

namespace People\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use People\Model\PersonTable;

class PeopleController extends AbstractActionController
{
    private $table;

    function __construct(PersonTable $table)
    {
        $this->table = $table;
    }

    public function indexAction() {
        return new ViewModel([
            "people" => "$this->table->fetchAll()",
        ]);
    }
}