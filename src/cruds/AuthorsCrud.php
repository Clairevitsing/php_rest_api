<?php

namespace App\cruds;
use PDO;

class AuthorsCrud extends ApiCrud
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        echo "AuthorsCrud created";
    }

    /**
     * CREATE A new article
     *
     * @param array $data title,content,user_id,createTime
     * @return int ID of the new article
     * @throws Exception
     */
    public function create(array $data): int
    {
            $query= "INSERT INTO authors VALUES (null, :name, :password,:registerTime)";
            $stmt = $this->pdo->prepare($query);
            $stmt ->execute([
                ':name' => $data['name'],
                ':password' => $data['password'],
                ':registerTime' => date('Y-m-d H:i:s')
            ]);

            return $this->pdo->lastInsertId();
    }


    public function readAll () :array
    {
        $stmt = $this -> pdo->query("SELECT * FROM authors");
        $authors = $stmt->fetchAll();

        return ($authors===false)? [] : $authors;
    }

    public function read (int $id) :?array
    {
        $query="SELECT * FROM authors WHERE id=:id";
        $stmt = $this -> pdo->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);
        $authors = $stmt->fetch();
        return ($authors===false)? null : $authors;

    }

    public function update (int $id, array $data): int
    {
        $query = "UPDATE authors SET name = :name, password = :password, registerTime = :registerTime WHERE id = :id";
        $stmt = $this -> pdo->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':password' => $data['password'],
            ':registerTime' => date('Y-m-d H:i:s')
        ]);
        return $stmt->rowCount();

    }
    
    public function delete (int $id) :int
    {
          $query = "DELETE FROM authors WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $id]);
         return $stmt->rowCount();
    }
}