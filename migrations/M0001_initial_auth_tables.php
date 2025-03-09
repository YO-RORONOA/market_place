<?php
// namespace App\migrations;
use App\migrations\Migration;

use App\core\Application;

class m0001_initial_auth_tables extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        // Create roles table
        $sql = "CREATE TABLE IF NOT EXISTS roles (
            id SERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        
        $db->pdo->exec($sql);
        
        // Insert default roles
        $sql = "INSERT INTO roles (name) VALUES 
            ('buyer'),
            ('vendor'),
            ('admin')
        ";
        
        $db->pdo->exec($sql);
        
        // Create users table
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id INT NOT NULL DEFAULT 1,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            verification_token VARCHAR(255),
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id)
        )";
        
        $db->pdo->exec($sql);
        
        // Create password_resets table
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        
        $db->pdo->exec($sql);
    }
    
    public function down()
    {
        $db = Application::$app->db;
        
        // Drop tables in reverse order due to foreign key constraints
        $sql = "DROP TABLE IF EXISTS password_resets;
                DROP TABLE IF EXISTS users;
                DROP TABLE IF EXISTS roles;";
                
        $db->pdo->exec($sql);
    }
}