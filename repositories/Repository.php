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

    public function findAll(array $conditions = [])
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName";

        if(!empty($conditions))
        {
            $sql .= " WHERE ";
            $whereCondition = [];

            foreach($conditions as $key => $value)
            {
                $whereCondition[] = "$key = :$key";

            }
            $sql .= implode(' AND ', $whereCondition);
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

    public function findOne(int $id)
    {
        $tableName = $this->table;
        $statement = $this->db->pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

}


