<?php

namespace Person\Model;

interface PersonRepositoryInterface
{
    public function findById(int $id): Person;

    public function findAll();
}
