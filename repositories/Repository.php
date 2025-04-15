<?php
// File: repositories/Repository.php (updated with error handling)

namespace App\repositories;

use App\core\Application;
use App\core\Database;
use App\core\exception\DatabaseException;

abstract class Repository implements RepositoryInterface
{
    protected Database $db;
    protected string $table;
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Application::$app->db;
    }

    /**
     * Find all records that match the given conditions
     *
     * @param array $conditions
     * @param bool $withTrashed
     * @param string|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws DatabaseException
     */
    public function findAll(array $conditions = [], bool $withTrashed = false, ?string $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName";

        $whereCondition = [];

        if (!$withTrashed) {
            $whereCondition[] = "deleted_at IS NULL";
        }

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereCondition[] = "$key = :$key";
            }
        }

        if (!empty($whereCondition)) {
            $sql .= " WHERE " . implode(' AND ', $whereCondition);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT :limit";
        }

        if ($offset) {
            $sql .= " OFFSET :offset";
        }

        try {
            $statement = $this->db->pdo->prepare($sql);

            if (!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    $statement->bindValue(":$key", $value);
                }
            }

            if ($limit) {
                $statement->bindValue(":limit", $limit, \PDO::PARAM_INT);
            }

            if ($offset) {
                $statement->bindValue(":offset", $offset, \PDO::PARAM_INT);
            }

            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to fetch records: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Find a single record by ID
     *
     * @param int $id
     * @param bool $withTrashed
     * @return array|null
     * @throws DatabaseException
     */
    public function findOne(int $id, bool $withTrashed = false): ?array
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName WHERE id = :id";
        
        if (!$withTrashed) {
            $sql .= " AND deleted_at IS NULL";
        }

        try {
            $statement = $this->db->pdo->prepare($sql);
            $statement->bindValue(":id", $id);
            $statement->execute();
            
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to fetch record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return int|false Last insert ID or false on failure
     * @throws DatabaseException
     */
    public function create(array $data)
    {
        $tableName = $this->table;
        $attributes = array_intersect_key($data, array_flip($this->fillable));
        
        if (empty($attributes)) {
            throw new DatabaseException('No valid fields to insert');
        }
        
        $params = array_map(fn($attr) => ":$attr", array_keys($attributes));

        $sql = "INSERT INTO $tableName (" . implode(',', array_keys($attributes)) . ") 
        VALUES (" . implode(',', $params) . ")";

        try {
            $statement = $this->db->pdo->prepare($sql);

            foreach ($attributes as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            $statement->execute();
            return $this->db->pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to create record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Update an existing record
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws DatabaseException
     */
    public function update(int $id, array $data): bool
    {
        $tableName = $this->table;
        $attributes = array_intersect_key($data, array_flip($this->fillable));
        
        if (empty($attributes)) {
            throw new DatabaseException('No valid fields to update');
        }
        
        $params = array_map(fn($attr) => "$attr = :$attr", array_keys($attributes));

        $sql = "UPDATE $tableName SET " . implode(',', $params) . " WHERE id = :id AND deleted_at IS NULL";
        
        try {
            $statement = $this->db->pdo->prepare($sql);
            $statement->bindValue(":id", $id);
            
            foreach ($attributes as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            return $statement->execute();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to update record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Soft delete a record
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function delete(int $id): bool
    {
        $tableName = $this->table;
        $currentTimestamp = date('Y-m-d H:i:s');
        $sql = "UPDATE $tableName SET deleted_at = :deleted_at WHERE id = :id AND deleted_at IS NULL";
        
        try {
            $statement = $this->db->pdo->prepare($sql);
            $statement->bindValue(":deleted_at", $currentTimestamp);
            $statement->bindValue(":id", $id);
            
            return $statement->execute();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to delete record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Restore a soft-deleted record
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function restore(int $id): bool
    {
        $tableName = $this->table;
        
        $sql = "UPDATE $tableName SET deleted_at = NULL WHERE id = :id AND deleted_at IS NOT NULL";
        
        try {
            $statement = $this->db->pdo->prepare($sql);
            $statement->bindValue(":id", $id);
            
            return $statement->execute();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to restore record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Permanently delete a record
     *
     * @param int $id
     * @return bool
     * @throws DatabaseException
     */
    public function forceDelete(int $id): bool
    {
        $tableName = $this->table;
        
        try {
            $statement = $this->db->pdo->prepare("DELETE FROM $tableName WHERE id = :id");
            $statement->bindValue(":id", $id);
            
            return $statement->execute();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to force delete record: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    /**
     * Get only trashed (soft-deleted) records
     *
     * @param array $conditions
     * @return array
     * @throws DatabaseException
     */
    public function onlyTrashed(array $conditions = []): array
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName WHERE deleted_at IS NOT NULL";
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND $key = :$key";
            }
        }
        
        try {
            $statement = $this->db->pdo->prepare($sql);
            
            if (!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    $statement->bindValue(":$key", $value);
                }
            }
            
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to fetch trashed records: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }
    
    /**
     * Count records based on conditions
     *
     * @param array $conditions
     * @return int
     * @throws DatabaseException
     */
    public function count(array $conditions = []): int
    {
        $tableName = $this->table;
        $sql = "SELECT COUNT(*) FROM $tableName WHERE deleted_at IS NULL";
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND $key = :$key";
            }
        }
        
        try {
            $statement = $this->db->pdo->prepare($sql);
            
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            
            $statement->execute();
            return (int)$statement->fetchColumn();
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Failed to count records: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }
    
    /**
     * Execute a raw SQL query
     *
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     * @throws DatabaseException
     */
    protected function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $statement = $this->db->pdo->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch (\PDOException $e) {
            throw new DatabaseException(
                'Query failed: ' . $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }
}