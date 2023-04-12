<?php
// echo "hello world !";

//charge l'autoloader PSR-4 de Composer
require_once __DIR__. '/../vendor/autoload.php';

use App\config\DbInitializer;
use App\config\ExceptionHandlerInitializer;
use Symfony\Component\Dotenv\Dotenv;

header('Content-Type: application/json; charset=utf-8');



// charge les variables d'environnement
$dotenv = new Dotenv();               //   这里是dotenv后面直接ctrl+espace， 然后回车键就可以添加上面use Symfony\Component\Dotenv\Dotenv;
$dotenv->loadEnv('.env');

//Définit un gestionnaire d'exceptions au niveau globale
ExceptionHandlerInitializer::registerGlobalExceptionHandler();
$pdo = DbInitializer::getPdoInstance();

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

//Collection de produits
if($uri === '/articles' && $httpMethod === 'GET'){
    $stmt = $pdo->query("SELECT * FROM articles");
    $articles = $stmt->fetchAll();
    echo json_encode($articles);
}

//Création de l'article
if($uri === '/articles' && $httpMethod === 'POST'){
   $data = json_decode(file_get_contents('php://input'), true);
   
   if(!isset($data['title']) || !isset($data['content'])){
        //gestion d'erreur
        http_response_code(422);
        echo json_encode([
            'error' => 'Title and content are required'
        ]);
          exit;
        }

        $query= "INSERT INTO articles VALUES (null, :title, :content, :user_id, :createTime)";
        $stmt = $pdo->prepare($query);
        $stmt ->execute([
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':user_id' => $data['user_id'],
            ':createTime' => date('Y-m-d H:i:s')
        ]);
        http_response_code(201);
        $insertedArticleId = $pdo ->lastInsertId();
        echo json_encode([
            'uri' => '/articles/' . $insertedArticleId
        ]);
}

//Ressource seule, type /article/{id}
//explode:
// /articles => ['', 'articles']
// /article/1 => ['', 'article', '1']
// /articles/coucou => ['', 'articles', 'coucou']

$uriParts = explode('/', $uri);
$isItemOperation = count($uriParts) === 3;

//Identifie si on est sur une opération sur un élément
if(!$isItemOperation){
    http_response_code(404);
    echo json_encode([
        'error' => 'Road Not found'
    ]);
    exit;
}

//Identifie si l'ID est valide (pas s'il existe en bdd)
$resourceTitle = $uriParts[1];
$id = intval($uriParts[2]);
if($id===0){
    http_response_code(400);
    echo json_encode([
        'error' => 'ID not found'
    ]);
    exit;
}

if($resourceTitle === 'articles' && $isItemOperation && $httpMethod==='GET'){
    $query = "SELECT * FROM articles WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id
    ]);
    $articles = $stmt->fetch();

    if($articles === false){
        http_response_code(404);
        echo json_encode([
            'error' => 'Article not found'
        ]);
        exit;
    }

    echo json_encode($articles);
}

// modification du élément
if($resourceTitle === 'articles' && $isItemOperation && $httpMethod==='PUT'){
    $data = json_decode(file_get_contents('php://input'), true);

    if(!isset($data['title']) ||!isset($data['content'])){
        //gestion d'erreur
        http_response_code(422);
        echo json_encode([
            'error' => 'Title and content are required'
        ]);
          exit;
    }
    $query = "UPDATE articles SET title = :title, content = :content, createTime = :createTime WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id,
        ':title' => $data['title'],
        ':content' => $data['content'],
        ':createTime' => date('Y-m-d H:i:s')
    ]);
    if($stmt->rowCount() === 0){
        http_response_code(404);
        echo json_encode([
            'error' => 'Article not found'
        ]);
        exit;
    }
    http_response_code(204);
}

//deletion du élément
if($resourceTitle === 'articles' && $isItemOperation && $httpMethod==='DELETE'){
    $query = "DELETE FROM articles WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':id' => $id
    ]);
    if($stmt->rowCount() === 0){
        http_response_code(404);
        echo json_encode([
            'error' => 'Article not found'
        ]);
        exit;
    }
    http_response_code(204);
}

// collection d'user
if($uri==='users' && $isItemOperation && $httpMethod==='GET'){
    $stmt = $pdo->prepare("SELECT * FROM users");
    $users = $stmt->execute();
    echo json_encode($users);
    exit;
}