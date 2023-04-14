<?php
namespace App\cruds;

use PDO;

abstract class ApiCrud
{

    public function __construct(PDO $pdo)
    {
    }

    abstract function create(array $data): int;

    abstract function readAll(): ?array;

    abstract function read(int $id): ?array;

    abstract function update(int $id, array $data): int;

    abstract function delete(int $id): int;
}
