<?php

use App\core\Application;
use App\core\Database;
use App\repositories\RepositoryInterface;




abstract class Repository implements RepositoryInterface
{
    protected Database $db;
    protected string $table;
    protected array $fillable = [];


    public function __construct()
    {
        $this->db = Application::$app->db;

    }

    public function findAll(array $conditions = [], bool $withTrashed = false)
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName";

        $whereCondition = [];

        if(!$withTrashed)
        {
            $whereCondition[] = "deleted_at IS NULL";
        }



        if(!empty($conditions))
        {
            foreach($conditions as $key => $value)
            {
                $whereCondition[] = "$key = :$key";

            }
        }

        if(!empty($whereCondition))
        {
        $sql .= " WHERE" . implode(' AND ', $whereCondition);
        }


        $statement = $this->db->pdo->prepare($sql);

        if(!empty($conditions))
        {
            foreach($conditions as $key => $value)
            {
                $statement->bindValue(":$key", $value);
            }
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findOne(int $id, bool $withTrashed = false)
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName WHERE id = :id";
        if(!$withTrashed)
        {
            $sql .= "AND deleted_at IS NULL";
        }

        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
    }


    public function create(array $data)
    {
        $tableName = $this->table;
        $attributes = array_intersect_key($data, array_flip($this->fillable));
        $params = array_map(fn($attr): string => ":attr", array_keys($attributes));

        $sql = "INSERT INTO $tableName (" . implode(',', array_keys($attributes)) . ") 
        VALUES (" . implode(',', $params) . ")";

        $statement = $this->db->pdo->prepare($sql);

        foreach ($attributes as $key => $value)
        {
            $statement->bindValue(":key", $value);

        }

        $statement->execute();
        return $this->db->pdo->lastInsertId();
    }


    public function update(int $id, array $data)
    {
        $tableName = $this->table;
        $attributes = array_intersect_key($data, array_flip($this->fillable));
        $params = array_map(fn($attr) => "$attr = :$attr", array_keys($attributes));
        
        $sql = "UPDATE $tableName SET " . implode(',', $params) . " WHERE id = :id AND deleted_at IS NULL";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        
        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);

    }
    return $statement->execute();
    }


    public function delete(int $id)
    {
        $tableName = $this->table;
        $currentTimestamp = date('d-m-Y, s:i:H');
        $sql = "UPDATE $tableName SET deleted_at = :deleted_at
        WHERE id = :id AND deleted_at IS NULL";
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(":deleted_at", $currentTimestamp);
    
        
        return $statement->execute();
    }


    public function restore(int $id)
    {
        $tableName = $this->table;
        
        $sql = "UPDATE $tableName SET deleted_at = NULL 
                WHERE id = :id AND deleted_at IS NOT NULL";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        
        return $statement->execute();
    }


    public function forceDelete(int $id)
    {
        $tableName = $this->table;
        $statement = $this->db->pdo->prepare("DELETE FROM $tableName WHERE id = :id");
        $statement->bindValue(":id", $id);
        
        return $statement->execute();
    }

    public function onlyTrashed(array $conditions = [])
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName WHERE deleted_at IS NOT NULL";
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $sql .= " AND $key = :$key";
            }
        }
        
        $statement = $this->db->pdo->prepare($sql);
        
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }
        
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


}


