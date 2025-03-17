<?php
namespace App\repositories;



use App\repositories\Repository;


class ProductRepository extends Repository
{   
    protected string $table = 'products';
    protected array $fillable = [
        'name', 'description', 'price', 'stock_quantity',
        'category_id', 'vendor_id', 'image_path', 'status'
    ];
    
    public function findByCategory(int $categoryId, int $limit, int $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}
        WHERE category_id = :category_id
        AND status = 'active'
        AND deleted_at IS NULL
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset";

        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':category_id', $categoryId);
        $statement->bindValue(':limit', $limit);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function findByVendor(int $vendorId, int $limit = 10, int $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}
        WHERE vendor_id = :vendor_id
        AND deleted_at IS NULL
        ORDER BY created_at DESC
        LIMIT :limit OFFSET :offset";

        $statment = $this->db->pdo->prepare($sql);
        $statment->bindValue(':vendor_id', $vendorId);
        $statment->bindValue(':limit', $limit);
        $statment->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statment->execute();

        return $statment->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function search(string $keyword, int $limit = 10, int $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}
        WHERE (name ILIKE :keyword OR description ILIKE :keyword)
        AND status = 'active'
        AND deleted_at IS NULL
        ORDER BY created_at DESC
        LIMIt :limit OFFSET :offset";

        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':keyword', "%$keyword%");
        $statement->bindValue(':limit', $limit);
        $statement->bindValue(':offset', $offset);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function countProducts(array $filters = []): mixed
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL";
        $params = [];

        if(!empty($filters))
        {
            foreach($filters as $key => $value)
            {
                $sql .= " AND $key = :$key";
                $params[":$key"] = $value;
            }
        }

        $statement = $this->db->pdo->prepare($sql);
        foreach($params as $key =>$value)
        {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        return $statement->fetchColumn();
    }
    
}