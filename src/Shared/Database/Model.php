<?php

declare(strict_types=1);

namespace App\Shared\Database;

use App\Config\Exceptions\DataBaseQueryException;
use App\Shared\Context;
use App\Shared\Database\Domain\ModelInterface;
use App\Shared\Database\Domain\QueryDebugLevelEnum;
use PDO;
use PDOException;
use PDOStatement;

class Model implements ModelInterface
{
    protected string $tableName = '';
    protected string $primaryKey = 'id';

    protected array $columns = ['*'];
    protected array $where = [];
    protected string $orderBy = '';
    protected int $limit = 0;
    protected int $offset = 1;
    private bool $isCount = false;
    protected string $selectSql = '';
    protected string $insertSql = '';
    protected string $updateSql = '';
    protected string $deleteSql = '';
    protected array $selectValues = [];
    protected array $insertValues = [];
    protected array $deleteValues = [];

    protected PDO $connection;
    protected Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
        $this->connection = $context->getConnection();
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

    public function count(): int
    {
        $this->isCount = true;
        $selectCount = $this->select(['COUNT(*) as total'])->get();
        
        if (count($selectCount) > 0) {
            return (int) $selectCount[0]['total'];
        }

        return 0;
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
            if (!empty($value)) {
                $this->where[] =  $value;
                continue;
            }
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

    public function offset(int $offset): ModelInterface
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        try {
            $columns = implode(', ', $this->columns);

            $where = $this->resolveWhere();

            $orderBy = $this->orderBy ? " ORDER BY $this->orderBy" : '';

            $limit = '';
            $offset = '';
            if (!$this->isCount) {
                $limit = $this->limit > 0 ? " LIMIT $this->limit" : '';
                $offset = $this->offset ? " OFFSET $this->offset" : '';
            }

            $sql = "SELECT $columns FROM $this->tableName$where$orderBy$limit$offset";
            $this->selectSql = $sql;

            $stmt = $this->connection->prepare($sql);

            $this->resolveBindValues($stmt);
            $this->debugSql();
            $stmt->execute();

            $this->reset();
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
            throw new DataBaseQueryException('Error executing select query', 0, $e);
        }
    }

    public function insert(array $data): array
    {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $insertSql = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
            $this->insertSql = $insertSql;
            $stmt = $this->connection->prepare($insertSql);
            foreach ($data as $key => $value) {
                $this->insertValues[] = $value;
                $stmt->bindValue(":$key", $value);
            }
            $result = $stmt->execute();

            $resultFind = [];
            if ($result) {

                $whereFind = [];

                foreach ($data as $key => $value) {
                    $whereFind[] = "$key = :$key";
                }

                $whereString = implode(' AND ', $whereFind);

                if ($whereString) {
                    $whereString = " WHERE $whereString";
                }

                $stmtFind = $this->connection->prepare("SELECT * FROM $this->tableName $whereString");

                foreach ($data as $key => $value) {
                    $stmtFind->bindValue(":$key", $value);
                }

                $stmtFind->execute();
                $resultFind = $stmtFind->fetch(PDO::FETCH_ASSOC) ?: [];
            }

            $this->debugSql();
            $this->reset();
            return $resultFind;
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing insert query', 0, $e);
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $setClause = '';
            foreach ($data as $key => $value) {
                $setClause .= "$key = :$key, ";
            }
            $setClause = rtrim($setClause, ', ');

            $sql = "UPDATE $this->tableName SET $setClause WHERE $this->primaryKey = :id";
            $this->updateSql = $sql;

            $stmt = $this->connection->prepare($sql);
            $pk = $this->primaryKey;
            $stmt->bindParam(":$pk", $id, PDO::PARAM_INT);

            foreach ($data as $key => $value) {
                $this->updateValues[] = $value;
                $stmt->bindValue(":$key", $value);
            }

            $result = $stmt->execute();
            $resultFind = [];
            if ($result) {

                $findSql = "SELECT * FROM $this->tableName WHERE $this->primaryKey = :id";

                $stmtFind = $this->connection->prepare($findSql);
                $stmtFind->bindValue(":$pk", $id, PDO::PARAM_INT);
                $stmtFind->execute();
                $resultFind = $stmtFind->fetch(PDO::FETCH_ASSOC) ?: [];
            }

            $this->debugSql();
            $this->reset();

            return $resultFind;
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing update query', 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM $this->tableName WHERE $this->primaryKey = :id";
            $this->deleteSql = $sql;
            $this->deleteValues[] = $id;

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            $this->debugSql();
            $this->reset();

            return $result;
        } catch (PDOException $e) {
            throw new DataBaseQueryException('Error executing delete query', 0, $e);
        }
    }

    /**
     * Debug the SQL query.
     */
    private function debugSql(): void
    {
        $level = (int) $this->context->getDatabaseDebugLevel();
        if ((int) $level === QueryDebugLevelEnum::SQL->value) {
            if (!empty($this->selectSql)) {
                error_log($this->selectSql);
            }

            if (!empty($this->insertSql)) {
                error_log($this->insertSql);
            }

            if (!empty($this->updateSql)) {
                error_log($this->updateSql);
            }

            if (!empty($this->deleteSql)) {
                error_log($this->deleteSql);
            }
        }

        if ((int) $level === QueryDebugLevelEnum::ALL->value) {
            if (!empty($this->selectSql)) {
                error_log($this->selectSql);
                error_log(json_encode($this->selectValues));
            }

            if (!empty($this->insertSql)) {
                error_log($this->insertSql);
                error_log(json_encode($this->insertValues));
            }

            if (!empty($this->updateSql)) {
                error_log($this->updateSql);
                error_log(json_encode($this->updateValues));
            }

            if (!empty($this->deleteSql)) {
                error_log($this->deleteSql);
                error_log(json_encode($this->deleteValues));
            }
        }
    }

    /**
     * Resolve the where clause for the query.
     * @return string The where clause.
     */
    private function resolveWhere(): string
    {

        $where = '';

        if (!empty($this->where)) {
            $where = ' WHERE ';
            foreach ($this->where as $value) {
                $field = $value['where'];
                $where .= "$field AND ";
            }
            $where = rtrim($where, ' AND ');
        }

        return $where;
    }

    /**
     * Resolve the bind values for the query.
     * @param PDOStatement $stmt The PDO statement.
     */
    private function resolveBindValues(PDOStatement &$stmt): void
    {
        foreach ($this->where as $value) {
            $this->selectValues[] = $value['value'];

            $bindString = ":" . $value['field'];

            if (is_array($value['value'])) {
                $bindString = [];
                foreach ($value['value'] as $key => $val) {
                    $stmt->bindValue(":{$value['field']}$key", $val);
                }
            } else {
                $stmt->bindValue($bindString, $value['value']);
            }
        }
    }

    /**
     * Reset the query values.
     */
    private function reset(): void
    {
        $this->columns = ['*'];
        $this->where = [];
        $this->orderBy = '';
        $this->isCount = false;
        $this->insertValues = [];
        $this->updateValues = [];
        $this->deleteValues = [];
        $this->selectValues = [];
        $this->selectSql = '';
        $this->insertSql = '';
        $this->updateSql = '';
        $this->deleteSql = '';
    }
}
