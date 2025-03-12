<?php
 
 use App\repositories\Repository;
 
 
 class CategoryRepository extends Repository
 {
     protected string $table = 'categories';
     protected array $fillable = ['name', 'parent_id'];
 
     public function getMainCategories()
     {
         $sql = "SELECT * FROM {$this->table}
         WHERE parent_id IS NULL
         AND deleted_at IS NULL
         ORDER BY name";
 
         $statment = $this->db->pdo->prepare($sql);
         $statment->execute();
 
         return $statment->fetchAll(\PDO::FETCH_ASSOC);
 
     }
 
     public function getSubcategories(int $parentId)
     {
         $sql = "SELECT * FROM {$this->table}
         WHERE parent_id = :parent_id
         AND deleted_at IS NULL
         ORDER BY name";
 
         $statement = $this->db->pdo->prepare($sql);
         $statement->bindValue(':parent_id', $parentId);
         $statement->execute();
 
         return $statement->fetchAll(\PDO::FETCH_ASSOC);
 
     }
 
     public function getCategoryPath(int $categoryId)
     {
         $path = [];
         $currentId = $categoryId;
         
         while ($currentId) {
             $category = $this->findOne($currentId);
             if (!$category) break;
             
             array_unshift($path, $category);
             $currentId = $category['parent_id'];
         }
         
         return $path;
     }
 
 }