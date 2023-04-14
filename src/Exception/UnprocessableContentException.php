<?php

namespace App\Exception;
use App\Http\ResponseCode;
use Exception;

class UnprocessableContentException extends Exception
{
    public function __construct(string $msg = "")
    {
        $this->code = ResponseCode::UNPROCESSABLE_CONTENT;
        $this->message = $msg;
    }
}