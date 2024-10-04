<?php

declare(strict_types=1);

namespace App\Shared\Database;

use App\Config\Exceptions\DataBaseQueryException;
use App\Shared\Database\Domain\ModelInterface;
use PDO;
use PDOException;

class Model implements ModelInterface
{
    protected string $tableName = '';
    protected string $primaryKey = 'id';

    protected array $columns = ['*'];
    protected array $where = [];
    protected string $orderBy = '';
    protected int $limit = 0;
    protected array $insertValues = [];
    protected array $updateValues = [];

    protected PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function select(array $columns = ['*']): ModelInterface
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(...$where): ModelInterface
    {
        $criteriaList = func_get_args();

        
        foreach ($criteriaList as $value) {
                $this->where[] =  $value;
                continue;
        }

        return $this;
    }

    public function orderBy(array $columns): ModelInterface
    {
        $this->orderBy = implode(', ', $columns);
        return $this;
    }

    public function limit(int $limit): ModelInterface
    {
        $this->limit = $limit;
        return $this;
    }

    public function get(): array
    {
        try {
            $columns = implode(', ', $this->columns);
            $where = '';
            if (!empty($this->where)) {
                $where = ' WHERE ';
                foreach ($this->where as $value) {
                    $field = $value['where'];
                    $where .= "$field AND ";
                }
                $where = rtrim($where, ' AND ');
            }
            $orderBy = $this->orderBy ? " ORDER BY $this->orderBy" : '';
            $limit = $this->limit ? " LIMIT $this->limit" : '';
            $stmt = $this->connection->prepare("SELECT $columns FROM $this->tableName$where$orderBy$limit");
            foreach ($this->where as $value) {

                $stmt->bindValue(":".$value['field'], $value['value']);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing query', 0, $e);
        }
    }

    public function first(): array
    {
        $result = $this->limit(1)->get();
        return $result[0] ?? [];
    }

    public function find(int $id): array
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM $this->tableName WHERE $this->primaryKey = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing query', 0, $e);
        }
    }

    public function insert(array $data): bool
    {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $stmt = $this->connection->prepare("INSERT INTO $this->tableName ($columns) VALUES ($placeholders)");
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing query', 0, $e);
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $setClause = '';
            foreach ($data as $key => $value) {
                $setClause .= "$key = :$key, ";
            }
            $setClause = rtrim($setClause, ', ');
            $stmt = $this->connection->prepare("UPDATE $this->tableName SET $setClause WHERE $this->primaryKey = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing query', 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM $this->tableName WHERE $this->primaryKey = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing query', 0, $e);
        }
    }
}
