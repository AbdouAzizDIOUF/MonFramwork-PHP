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
        $query =  new \Framework\Database\PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY create_at DESC',
            'SELECT COUNT(id) FROM posts',
            Post::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Récupére un article à partire de son id
     * @param $id
     * @return [stdClass()] [description]
     */
    public function find(int $id): Post
    {
        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id=?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch();
    }
}
