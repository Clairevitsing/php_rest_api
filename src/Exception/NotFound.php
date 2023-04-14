<?php

namespace App\Exception;
use App\Http\responseCode;

use Exception;

class NotFound extends Exception
{
    public function __construct(string $msg)
    {
        $this->code = ResponseCode::NOT_FOUND;
        $this -> message = $msg;
    }
}