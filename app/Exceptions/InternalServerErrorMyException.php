<?php

namespace App\Exceptions;

use Exception;

class InternalServerErrorMyException extends Exception
{
    public function __construct($errorMessage)
    {
        $this->message = $errorMessage;
    }

    public function render(){
        return response()->json([
            'message' => 'server error!',
            'error' => [
                "message" => $this->message
            ] 
        ], 500);
    }
}
