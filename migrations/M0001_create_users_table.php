<?php

use App\core\Application;
use App\core\migrations\Migration;


class M0001_create_users_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id INT NOT NULL DEFAULT 1, -- 1 for buyer
            status VARCHAR(50) NOT NULL DEFAULT 'pending', -- pending, active, suspended, deleted
            verification_token VARCHAR(255),
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        $db->pdo->exec($sql);

        $sql = "CREATE TABLE IF NOT EXISTS roles(
        id SERIAL PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL)";

        $db->pdo->exec($sql);


        $sql = "INSERT INTO roles (name) VALUES
        ('buyer'),
        ('vendor'),
        ('admin')
        ";

        $db->pdo->exec($sql);

    }


    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE users, DROP TABLE roles;";
        $db->pdo->exec($sql);
    }

}