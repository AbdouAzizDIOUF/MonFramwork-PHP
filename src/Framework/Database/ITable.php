<?php


namespace App\Framework\Database;


use Pagerfanta\Pagerfanta;

interface ITable
{
    /**
     * Pagine les articles
     *
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findPagination(int $perPage, int $currentPage): Pagerfanta;

    /**
     * Récupère une liste clef valeur de nos enregistrements
     *
     * @return array
     */
    public function findList(): array;

    /**
     * Récupère un élément à partir de son ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool;

    /**
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool;

    public function delete(int $id): bool;

    public function exists($id): bool;
}