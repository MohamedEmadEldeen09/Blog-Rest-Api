<?php

namespace App\Exceptions;

use Exception;

class UnAuthorizedToMakeActionMyException extends Exception
{
    public function __construct($errorMessage)
    {
        $this->message = $errorMessage;
    }

    public function render(){
        return response()->json([
            'error' => 'UnAuthorized to make this action!',
            'details' => [
                "message" => $this->message
            ] 
        ], 403);
    }
}
