<?php

namespace App\config;

use throwable;

class ExceptionHandlerInitializer
{
   public static function registerGlobalExceptionHandler()
   {
    set_exception_handler(function(throwable $e){
        http_response_code((500));
        echo json_encode([
            'error' => 'une erreur est survenue',
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    });
   }
}