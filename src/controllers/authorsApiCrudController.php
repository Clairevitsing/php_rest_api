<?php
namespace App\controllers;

use App\Exception\UnprocessableContentException;
use App\cruds\AuthorsCrud;
use App\controllers\ApiCrudController;
use App\http\ResponseCode;
use Exception;

class authorsApiCrudController extends ApiCrudController
{
  
    public function processRequest(): void
    {
        echo "authorsApiCrudController processRequest";

        $this -> Crud = new AuthorsCrud($this->pdo);

        if ($this ->uri === "/authors") {
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
        if (!isset($data['name'])) {
            throw new UnprocessableContentException("name is required");
        }
        
    }
   
    public function resourceOperation(int $id): void
    {
        $author = $this->Crud->read($id);
        
        if ( ! $author) {
            http_response_code(ResponseCode::NOT_FOUND);
            echo json_encode(["message" => "Author not found"]);
            return;
        }
        
        switch ($this -> httpMethod) {
            case "GET":
                echo json_encode($author);
                break;
                
            case "put":
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
                    "message" => "authors $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->Crud->delete($id);
               
                echo json_encode([
                    "message" => "authors $id deleted",
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
        switch ($this -> httpMethod) {
            case "GET":
                echo json_encode($this->Crud->readAll());
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(ResponseCode::UNPROCESSABLE_CONTENT);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->Crud->create($data);
                
                http_response_code(ResponseCode::CREATED);
                echo json_encode([
                    "message" => "Author created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(ResponseCode::METHOD_NOT_ALLOWED);
                header("Allow: GET, POST");
        }
    }
    
  
}

