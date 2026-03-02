<?php

namespace App\Contracts;

interface Investment
{
    public function getBalance() : float;
    
    public function setBalance(float $balance) : void;
}