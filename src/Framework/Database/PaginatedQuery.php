<?php
namespace Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;
use PDO;
use Traversable;

class PaginatedQuery implements AdapterInterface
{
    private $pdo;
    private $query;
    private $countQuery;
    /**
     * @var string|null
     */
    private $entity;


    /**
     * PaginatedQuery constructor
     * @param PDO $pdo
     * @param string $query Requete permettant de recuperer x resultats
     * @param string $countQuery Requete permettant de compter le nombre de resultats totals
     * @param string|null $entity
     */
    public function __construct(PDO $pdo, string $query, string $countQuery, ?string $entity)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
    }
    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     * @return array|Traversable The slice.
     */
    public function getSlice($offset, $length): array
    {
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        $statement->bindParam('offset', $offset, PDO::PARAM_INT);
        $statement->bindParam('length', $length, PDO::PARAM_INT);
        if ($this->entity){
            $statement->setFetchMode(PDO::FETCH_CLASS, $this->entity);// retourne un nouvel object de la classe post
        }
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * returns the numbers of resultats
     * @return integer the number of results
     */
    public function getNbResults(): int
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }
}
