<?php

namespace App\Exception;
use App\Http\responseCode;

use Exception;

class MethodNotAllowed extends Exception
{
    public function __construct(string $msg)
    {
        $this->code = ResponseCode::METHOD_NOT_ALLOWED;
        $this -> message = $msg;
    }
}