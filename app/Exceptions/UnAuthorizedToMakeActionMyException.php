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
            'message' => 'UnAuthorized to make this action!',
            'details' => [
                "error" => $this->message
            ] 
        ], 403);
    }
}
