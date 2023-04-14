<?php

namespace App\http;

class responseCode
{
    //Success
    const OK = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;

    //Client Error
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const UNPROCESSABLE_CONTENT = 422;

    //Server Error
    const INTERNAL_SERVER_ERROR = 500;
}