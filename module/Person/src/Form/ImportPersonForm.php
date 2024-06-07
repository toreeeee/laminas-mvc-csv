<?php

namespace Person\Form;

use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;
use Laminas\InputFilter;

class ImportPersonForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('import-person-form');

        $this->add([
            'type' => File::class,
            'name' => 'file',
            'options' => [
                'label' => 'Select CSV file:',
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

        $this->addInputFilter();
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        $fileInput = new InputFilter\FileInput('file');
        $fileInput->setRequired(true);

        $fileInput->getValidatorChain()
            ->attachByName('filesize', ['max' => 204800])
            ->attachByName('filemimetype', ['mimeType' => 'text/csv,text/plain']);

        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}
