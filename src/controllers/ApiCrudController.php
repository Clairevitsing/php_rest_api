<?php
namespace App\controllers;

use App\cruds\ApiCrud;
use App\Exception\MethodNotAllowed;
use App\Exception\NotFound;
use App\http\ResponseCode;
use PDO;

abstract class ApiCrudController
{
    protected const ACCEPTED_COLLECTION_METHODS = ["GET", "POST"];
    protected const ACCEPTED_RESOURCE_METHODS = ["GET", "PUT", "DELETE"];

      protected ApiCrud $Crud;

    public function __construct(
        protected PDO $pdo,
        protected string $uri,
        protected string $httpMethod
       
    ) {
        
    }
    
   
        /**
     * Check if the used method is acceptable for a collection operation. Pass without doing anything if it's good, throw an Exception if it's not.
     *
     * @return void
     * @throws Exception
     */
    protected function processCollectionRequest(): void
    {
        echo "processCollectionRequest\n";

        if (!in_array($this->httpMethod, self::ACCEPTED_COLLECTION_METHODS)) {
            throw new MethodNotAllowed("Please choose an accepted method for a collection request : " . implode(" - ", self::ACCEPTED_COLLECTION_METHODS));
        }
    }

    /**
     * Check if the used method is acceptable for a resource operation. Pass without doing anything if it's good, throw an Exception if it's not. 
     *
     * @return void
     * @throws Exception
     */
    protected function processResourceRequest(): void
    {
        echo "processResourceRequest\n";

        if (!in_array($this->httpMethod, self::ACCEPTED_RESOURCE_METHODS)) {
            throw new MethodNotAllowed("accepted method: " . implode(" - ", self::ACCEPTED_RESOURCE_METHODS));
        }
    }

    /**
     * Check if the id is in the database. Pass without doing anything if it's good, throw an Exception if it's not
     *
     * @param integer $id
     * @return void
     * @throws Exception
     */
    protected function getValidationId(int $id): void
    {
        if ($id === 0 || $this->Crud->read($id) === null) {
            throw new NotFound("articles not found");
            exit;
        }
    }

    abstract function getValidationErrors(array $data): void;

    abstract function collectionOperation(): void;

    abstract function resourceOperation(int $id): void;
}
