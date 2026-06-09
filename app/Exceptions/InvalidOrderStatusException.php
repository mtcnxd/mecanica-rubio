<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderStatusException extends Exception
{
    public function report()
    {
        \Log::info($this->getMessage());
    }

    public function render()
    {
        return response()->view('error-template', [
            'message' => $this->getMessage()
        ]);
    }
}
