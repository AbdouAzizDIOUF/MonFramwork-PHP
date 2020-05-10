<?php


namespace App\Framework\Database;


use Framework\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use PDO;

class Table implements ITable
{
    private $pdo;
    /**
     * @var $table
     */
    protected $table;
    /**
     * @var string|null
     */
    protected $entity;

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
        $query =  new PaginatedQuery($this->pdo, $this->paginationQuery(), "SELECT COUNT(id) FROM {$this->table}", $this->entity);
        $fanta = new Pagerfanta($query);
        $fanta->setMaxPerPage($perPage);
        $fanta->setCurrentPage(($currentPage >= $fanta->getNbPages()) ? $fanta->getNbPages() : $currentPage);

        return $fanta;
    }

    /**
     * @return string
     */
    protected function paginationQuery(): string
    {
        return "SELECT * FROM {$this->table}";
    }


    public function findList(): array
    {
        $list = [];
        $results = $this->pdo
            ->query("SELECT id, name FROM {$this->table}")
            ->fetchAll(PDO::FETCH_NUM);

        foreach ($results as $result){
            $list[$result[0]] = $result[1];
        }

        return $list;
    }

    /**
     * Récupére un article à partir de son id
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $query->execute([$id]);
        if ($this->entity){
            $query->setFetchMode(PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetch() ?:null;
    }

    /**
     * Mise à jour d'un enregistrement
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldsQuerry($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");

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
        $values = implode(', ', array_map(static function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = implode(', ', $fields);
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");

        return $statement->execute($params);
    }

    /**
     * Suppression d'un enregistrement
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");

        return $statement->execute([$id]);
    }


    private function buildFieldsQuerry($params): string
    {
        return implode(', ', array_map(static function ($field) {
            return "$field=:$field";
        }, array_keys($params)));
    }

    public function exists($id): bool
    {
        $query = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        return $query->fetchColumn() !== false;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

}