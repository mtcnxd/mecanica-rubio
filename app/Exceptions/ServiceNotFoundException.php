<?php

namespace App\Exceptions;

use Exception;

class ServiceNotFoundException extends Exception
{
    public function render()
    {
        return response()->view('error-template', [
            'message' => $this->getMessage()
        ]);
    }
}
