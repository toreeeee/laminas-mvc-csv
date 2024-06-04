<?php

namespace People\Model;

use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class Person implements InputFilterAwareInterface
{
    public $id;
    public $first_name;
    public $last_name;
    public $salary;
    public $birthday;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->first_name = !empty($data['first_name']) ? $data['first_name'] : null;
        $this->last_name = !empty($data['last_name']) ? $data['last_name'] : null;
        $this->salary = !empty($data['salary']) ? $data['salary'] : null;
        $this->birthday = !empty($data['birthday']) ? $data['birthday'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        // TODO: Implement setInputFilter() method.
    }

    public function getInputFilter()
    {
        // TODO: Implement getInputFilter() method.
    }
}