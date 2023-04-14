<?php
// echo "hello world !";

//charge l'autoloader PSR-4 de Composer
require_once __DIR__. '/../vendor/autoload.php';

use App\config\DbInitializer;
use App\config\ExceptionHandlerInitializer;
use App\controllers\articlesApiCrudController;
use App\controllers\anthorsApiCrudController;
use App\Exception\UnprocessableContentException;
use Symfony\Component\Dotenv\Dotenv;
use App\http\ResponseCode;

header('Content-Type: application/json; charset=utf-8');

set_error_handler(function () {
    http_response_code(ResponseCode::INTERNAL_SERVER_ERROR);
    echo json_encode([
        'error' => 'Une erreur est survenue'
    ]);
});

// charge les variables d'environnement
$dotenv = new Dotenv();               //   这里是dotenv后面直接ctrl+espace， 然后回车键就可以添加上面use Symfony\Component\Dotenv\Dotenv;
$dotenv->loadEnv('.env');

//Définit un gestionnaire d'exceptions au niveau globale
ExceptionHandlerInitializer::registerGlobalExceptionHandler();
$pdo = DbInitializer::getPdoInstance();

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];
// const RESOURCES = ['articles'];
// const RESOURCES = ['anthors'];

//Ressource seule, type /article/{id}
//explode:
// /articles => ['', 'articles']
// /article/1 => ['', 'article', '1']
// // /articles/coucou => ['', 'articles', 'coucou']

// $uriParts = explode('/', $uri);
// $isItemOperation = count($uriParts) === 3;
// $articlesCrud = new ArticlesCrud($pdo);
if(str_contains($uri, "/articles")){
    $controller = new articlesApiCrudController($pdo, $uri, $httpMethod);
    $controller->processRequest();
} else if (str_contains($uri, "/anthors")) {
    $controller = new anthorsApiCrudController($pdo, $uri, $httpMethod);
    $controller->processRequest();
} else {
    http_response_code(ResponseCode::BAD_REQUEST);
    echo json_encode([
        'error' => "An error occured",
        'code' => 400,
        'message' => "can't find the resource"
    ]);
}