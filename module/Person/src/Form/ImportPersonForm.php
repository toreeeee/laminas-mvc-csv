<?php

namespace Person\Form;

use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;

class ImportPersonForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('import-person-form');

        $this->add([
            'type' => File::class,
            'name' => 'file',
            'options' => [
                'label' => 'Select csv file',
            ],
            'attributes' => [
                "accept" => ".csv",
                "required" => true
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Upload',
                'id' => 'submitbutton',
            ],
        ]);
    }
}
