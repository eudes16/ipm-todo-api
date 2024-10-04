<?php

declare(strict_types=1);

namespace App\Shared\Database\Domain;


/**
 * Interface ModelInterface
 * @package App\Shared\Database\Domain
 * 
 * @property string $tableName
 * @property string $primaryKey
 * @property array $columns
 * @property array $where
 * @property array $orderBy
 * @property int $limit
 * @property array $insertValues
 * @property array $updateValues
 */
interface ModelInterface
{
    
    public function getTableName(): string;
    public function getPrimaryKey(): string;
    public function getColumns(): array;

    public function select(array $columns = ['*']): ModelInterface;
    public function where(array ...$where): ModelInterface;
    public function orderBy(array $column): ModelInterface;
    public function limit(int $limit): ModelInterface;
    public function get(): array;
    public function first(): array;
    public function find(int $id): array;
    public function insert(array $insertValues): bool;
    public function delete(int $id): bool;
    public function update(int $id, array $updateValues): bool;

}
