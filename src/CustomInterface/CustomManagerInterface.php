<?php

namespace App\CustomInterface;

interface CustomManagerInterface
{
    public function save(object $entityObject);

    public function delete(object $entityObject);

    public function update(object $entityObject);

    public function getList(object $entityObject);
}