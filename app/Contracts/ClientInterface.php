<?php

namespace App\Contracts;

interface ClientInterface
{
    public function createService(array $data) : void ;
}