<?php

interface PersonRepositoryInterface
{
    function getById(int $id): Person;

    function getAll(): array;
}
