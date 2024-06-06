<?php

namespace Person\Form;

use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\DateSelect;
use Laminas\Form\Form;
use Laminas\Filter;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Validator\Regex as RegexValidator;
use Person\Model\Person;

class PersonForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('person');

        $this->setHydrator(new ReflectionHydrator());
        $this->setObject(new Person("", "", "", 0, 0));

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'first_name',
            'options' => [
                'label' => 'Vorname',
            ],
            'filters' => [
                ['name' => Filter\StringTrim::class],
                ['name' => Filter\StringToLower::class],
            ],
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'last_name',
            'options' => [
                'label' => 'Nachname',
            ],
            'filters' => [
                ['name' => Filter\StringTrim::class],
                ['name' => Filter\StringToLower::class],
            ],
        ]);

        $this->add([
            'type' => DateSelect::class,
            'name' => 'birthday',
            'options' => [
                'label' => 'Geburtstag',
                "render_delimiters" => false
            ],
        ]);

        $this->add([
            'type' => Number::class,
            'name' => 'salary',
            'options' => [
                'label' => 'Gehalt',
            ],
            "validators" => [
                new RegexValidator('/^[+]?\d+([.]\d+)?/')
            ],
            "attributes" => [
                "min" => 1,
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
