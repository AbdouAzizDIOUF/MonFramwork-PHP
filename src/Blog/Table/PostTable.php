<?php
namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use PDO;

class PostTable
{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Pagine les articles
     * @param int $perPage
     * @param int $currentPage
     * @return pagerFanta
     */
    public function findPagination(int $perPage, int $currentPage): Pagerfanta
    {
        $query =  new PaginatedQuery($this->pdo, 'SELECT * FROM posts ORDER BY create_at DESC', 'SELECT COUNT(id) FROM posts', Post::class);
        $fanta = new Pagerfanta($query);
        $fanta->setMaxPerPage($perPage);
        $fanta->setCurrentPage(($currentPage >= $fanta->getNbPages()) ? $fanta->getNbPages() : $currentPage);

        return ($fanta);
    }

    /**
     * Récupére un article à partir de son id
     * @param int $id
     * @return Post|null
     */
    public function find(int $id): ?Post
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id=?');
        $query->execute([$id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        return $query->fetch() ?:null;
    }

    /**
     * Met à jour d'un enregistrement
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldsQuerry($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("UPDATE posts SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }

    /**
     * L'enregistrement dans la base de donnees
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = array_map(static function ($field) {
            return ':' . $field;
        }, $fields);

        $statement = $this->pdo->prepare('INSERT INTO posts(' . implode(', ', $fields) . ') VALUES(' . implode(', ', $values) . ') ');

        return $statement->execute($params);
    }

    /**
     * Suppression d'un enregistrement
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');

        return $statement->execute([$id]);
    }


    private function buildFieldsQuerry($params): string
    {
        return implode(', ', array_map(static function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
