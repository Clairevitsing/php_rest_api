<?php

namespace App\cruds;

use PDO;

class ArticlesCrud extends ApiCrud
{
   
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     *
     * @param array $data title,content,user_id,createTime
     * @return int ID of the new article
     * @throws Exception
     */
    public function create(array $data): int
    {
            $query= "INSERT INTO articles VALUES (null, :title, :content, :author_id, :createTime)";
            $stmt = $this->pdo->prepare($query);
            $stmt ->execute([
                ':title' => $data['title'],
                ':content' => $data['content'],
                ':author_id' => $data['author_id'],
                ':createTime' => date('Y-m-d H:i:s')
            ]);

            return $this->pdo->lastInsertId();
    }


    public function readAll () :array
    {
        $stmt = $this -> pdo->query("SELECT * FROM articles");
        $articles = $stmt->fetchAll();

        return ($articles===false)? [] : $articles;
    }

    public function read (int $id) :?array
    {
        $query="SELECT * FROM articles WHERE id=:id";
        $stmt = $this -> pdo->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);
        $articles = $stmt->fetch();
        return ($articles===false)? null : $articles;

    }

    public function update (int $id, array $data): int
    {
        $query = "UPDATE articles SET title = :title, content = :content, createTime = :createTime WHERE id = :id";
        $stmt = $this -> pdo->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':createTime' => date('Y-m-d H:i:s')
        ]);
        return $stmt->rowCount();

    }
    
    public function delete (int $id) :int
    {
          $query = "DELETE FROM articles WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $id]);
         return $stmt->rowCount();
    }
}