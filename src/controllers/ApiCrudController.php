<?php
namespace App\controllers;

use App\cruds\ApiCrud;
use App\Exception\MethodNotAllowed;
use App\Exception\NotFound;
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
     * 檢查使用的方法是否適合收集數據。 如果可以就通過，如果不行，則拋出異常。
     *
     * @return void
     * @throws Exception
     */
    protected function processCollectionRequest(): void
    {
        // echo "processCollectionRequest\n";

        if (!in_array($this->httpMethod, self::ACCEPTED_COLLECTION_METHODS)) {
            throw new MethodNotAllowed("Please choose an accepted method for a collection request : " . implode(" - ", self::ACCEPTED_COLLECTION_METHODS));
        }
    }

    /**
     * 檢查使用的方法是否適合資源收集。 如果可以就通過，如果不行，則拋出異常。
     *
     * @return void
     * @throws Exception
     */
    protected function processResourceRequest(): void
    {
        // echo "processResourceRequest\n";

        if (!in_array($this->httpMethod, self::ACCEPTED_RESOURCE_METHODS)) {
            throw new MethodNotAllowed("accepted method: " . implode(" - ", self::ACCEPTED_RESOURCE_METHODS));
        }
    }

    /**
     * 確認id是否存在數據庫中。 如果在就通過，如果不在就拋出異常
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
