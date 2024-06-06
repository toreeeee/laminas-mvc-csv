<?php

namespace Person\Model;

interface PersonRepositoryInterface
{
    public function getById(int $id): Person;

    public function getAll();
}
