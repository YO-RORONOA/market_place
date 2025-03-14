<?php
use App\core\Application;
use App\migrations\Migration;

class drop_all_tables extends Migration
{
    public function up()
    {
        // This migration only drops tables, so up() doesn't create anything
        return;
    }
    
    public function down()
    {
        $db = Application::$app->db;
        
        // First disable foreign key checks to avoid constraint errors
        $db->pdo->exec("SET session_replication_role = 'replica';");
        
        // Drop tables in reverse order to avoid foreign key constraint issues
        $tables = [
            'products',
            'categories',
            'password_resets',
            'users',
            'roles',
            'migrations'
        ];
        
        foreach ($tables as $table) {
            $db->pdo->exec("DROP TABLE IF EXISTS $table CASCADE;");
            echo "Dropped table $table" . PHP_EOL;
        }
        
        // Re-enable foreign key checks
        $db->pdo->exec("SET session_replication_role = 'origin';");
        
        echo "All tables have been dropped." . PHP_EOL;
    }
}