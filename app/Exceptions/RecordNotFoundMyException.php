<?php

namespace App\Exceptions;

use Exception;

class RecordNotFoundMyException extends Exception
{
    public function render(){
        return response()->json([
            'error' => 'Record not found!'
        ], 404);
    }
}
