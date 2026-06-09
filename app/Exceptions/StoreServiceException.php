<?php

namespace App\Exceptions;

use Exception;

class StoreServiceException extends Exception
{
    public function report()
    {
        \log::info($this->getMessage());
    }
}
