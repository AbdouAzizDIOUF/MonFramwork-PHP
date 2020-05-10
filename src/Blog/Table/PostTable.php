<?php
namespace App\Blog\Table;

use App\Blog\Entity\Post;
use App\Framework\Database\Table;

class PostTable extends Table
{

    protected $entity = Post::class;

    protected $table = 'posts';

    protected function paginationQuery(): string
    {
        return $this->findAllDESC();
    }



    private function findAllDESC(): string
    {
        return "SELECT p.id, p.name, c.name category_name
            FROM {$this->table} AS p
            LEFT JOIN categorie AS c ON p.category_id=c.id
            ORDER BY create_at DESC";
    }

}
