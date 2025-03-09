<?php

namespace App\core\Dbmodal;

use App\core\Application;
use App\core\Model;


/**
 * Class Dbmodal
 *
 * This class handles database operations and interactions.
 * It provides methods to connect to the database, execute queries,
 * and manage transactions.
 *
 * @package Market\Core\Dbmodal
 */


abstract class Dbmodal extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    

    /**
     * Save the current state of the object to the database.
     *
     * @return bool Returns true on success, false on failure.
     */
 
    public function save()
    {
        $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn($attr) => ":$attr", $attributes);
    $statement = self::prepare("INSERT INTO \"users\" (" . implode(',', $attributes) . ")
    VALUES(" . implode(',', $params) . ")");
    foreach ($attributes as $attribute)
    {
        $statement->bindValue(":$attribute", $this->$attribute);
    }
    $statement->execute();
    return true;

    }

    public function update()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => "$attr = :$attr", $attributes);

        $sql = "UPDATE $tableName SET " . implode(',', $params) . " 
            WHERE id = :id";

        $statement = self::prepare($sql);

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->bindValue(":id", $this->id);
        $statement->execute();

        return true;
    }


    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
        
    }

}