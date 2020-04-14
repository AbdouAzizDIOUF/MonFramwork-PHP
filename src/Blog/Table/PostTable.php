<?php
namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Pagerfanta\Pagerfanta;

class PostTable
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Pagine les articles
     * @return pagerFanta
     */
    public function findPagination(int $perPage, int $currentPage): Pagerfanta
    {
        $query =  new \Framework\Database\PaginatedQuery($this->pdo, 'SELECT * FROM posts ORDER BY create_at DESC', 'SELECT COUNT(id) FROM posts', Post::class);
        return (new Pagerfanta($query))
        ->setMaxPerPage($perPage)
        ->setCurrentPage($currentPage);
    }

    /**
     * Récupére un article à partire de son id
     * @param $id
     * @return [stdClass()] [description]
     */
    public function find(int $id): ?Post
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id=?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch() ?:null;
    }

     /**
     * Met à jour un enregistrement au niveau de la bas de donns
     * @param  int    $id     [description]
     * @param  array  $param [description]
     * @return bool         [description]
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldsQuerry($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("UPDATE posts SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }

    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $statement = $this->pdo->prepare("INSERT INTO posts(" . join(', ', $fields) . ") VALUES(" . join(', ', $values) . ") ");
        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');
        return $statement->execute([$id]);
    }
    private function buildFieldsQuerry($params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
