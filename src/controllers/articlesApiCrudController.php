<?php
namespace App\controllers;

use App\Exception\UnprocessableContentException;
use App\cruds\ArticlesCrud;
use App\controllers\ApiCrudController;
use App\http\ResponseCode;
use Exception;

class articlesApiCrudController extends ApiCrudController
{
    
    public function processRequest(): void
    {
        $this -> Crud = new ArticlesCrud($this->pdo);
        echo "processRequest ".$this ->uri."\n";
        if ($this ->uri === "/articles") {
            try {
            $this->processCollectionRequest();
            $this->collectionOperation();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
        } else {
            try{
                $this->processResourceRequest();
                $uriExploded=explode('/',$this->uri);
                $id = intval(end($uriExploded));
                $this->getValidationId($id);
                $this->resourceOperation($id);
                
            }catch(Exception $e){
                echo $e->getMessage();
            } 
        }
    }
    
    
    public function getValidationErrors(array $data): void
    {
        if (!isset($data['title']) || !isset($data['content'])) {
            throw new UnprocessableContentException("name or content is required");
        }
        
    }
   
    public function resourceOperation(int $id): void
    {
        // echo "resourceOperation\n";
        $article = $this->Crud->read($id);
        // echo "article";
        if ( ! $article) {
            http_response_code(404);
            echo json_encode(["message" => "Article not found"]);
            return;
        }
        
        switch ($this -> httpMethod) {
            case "GET":
                echo json_encode($article);
                break;
                
            case "PUT":
                // echo "put";
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(ResponseCode::UNPROCESSABLE_CONTENT);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
               
                $rows = $this->Crud->update($id, $data);
                http_response_code(ResponseCode::NO_CONTENT);
                echo json_encode([
                    "message" => "articles $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->Crud->delete($id);
                
                echo json_encode([
                    "message" => "article $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(ResponseCode::METHOD_NOT_ALLOWED);
                header("Allow: GET, PUT, DELETE");
        }
    }
    
    public function collectionOperation(): void
    {
        // echo $this -> httpMethod."\n";
        switch ($this -> httpMethod) {
            case "GET":
                echo json_encode($this->Crud->readAll());
                break;
                
            case "POST":
                // echo "1\n";
                // echo file_get_contents("php://input");
                // echo "2\n";
                // echo json_decode(file_get_contents("php://input"));
                // echo "3\n";
                $data = (array) json_decode(file_get_contents("php://input"));
                echo $data['title']."\n";
                echo "4\n";

                $errors = $this->getValidationErrors($data);
                //echo $errors."\n";               
                if ( ! empty($errors)) {
                    http_response_code(ResponseCode::UNPROCESSABLE_CONTENT);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                // echo "5\n";
                $id = $this->Crud->create($data);
                echo "6\n";
                http_response_code(ResponseCode::CREATED);
                echo json_encode([
                    "message" => "Articles created",
                    "id" => $id
                ]);
                // echo "7\n";
                break;
            
            default:
                http_response_code(ResponseCode::METHOD_NOT_ALLOWED);
                header("Allow: GET, POST");
        }
    }
    
  
}

