<?php

namespace Album\Controller\Person\src\Form;

use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;

class PersonUploadForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('person');

        $this->add([
            "name" => "file",
            "type" => File::class,
            "required" => true,
            'attributes' => [
                "accept" => ".csv"
            ]

        ]);

        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }
}
