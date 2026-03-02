<?php

namespace App\Contracts\Repositories;

use App\Contracts\InvestmentInterface;

class InvestmentRepository implements InvestmentRepositoryInterface
{
    public function getBalance() : float
    {
        return 0.0;
    }
    
    public function setBalance(float $balance) : void
    {
        
    }
}