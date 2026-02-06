<?php

namespace App\Contracts;

interface ServiceInterface
{
    public function changeStatus(string $status) : void ;
}