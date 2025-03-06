<?php

namespace App\repositories;

interface RepositoryInterface
{
    public function findall(array $condition = []);
    public function findOne(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id); 
    public function restore(int $id);
    public function forceDelte(int $id);

}
