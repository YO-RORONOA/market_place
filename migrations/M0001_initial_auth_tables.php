<?php
// namespace App\migrations;
use App\migrations\Migration;

use App\core\Application;

class m0001_initial_auth_tables extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        
        $sql = "CREATE TABLE IF NOT EXISTS roles (
            id SERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        
        $db->pdo->exec($sql);
        
        $sql = "INSERT INTO roles (name) VALUES 
            ('buyer'),
            ('vendor'),
            ('admin')
        ";
        
        $db->pdo->exec($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            verification_token VARCHAR(255),
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        
        $db->pdo->exec($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS user_roles (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL,
            role_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
            UNIQUE(user_id, role_id)
        )";
        
        $db->pdo->exec($sql);
        
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
        
        $sql = "DROP TABLE IF EXISTS password_resets;
                DROP TABLE IF EXISTS user_roles;
                DROP TABLE IF EXISTS users;
                DROP TABLE IF EXISTS roles;";
                
        $db->pdo->exec($sql);
    }
}