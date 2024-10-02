<?php

namespace App\Repositories\UserRepositories;

use Ramsey\Collection\Collection;

interface UserRepositoryInterface
{
    public function fetchAllUsers();
    public function getUserById(int $id);
    public function createUser(array $data);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id): bool;
    public function searchUsers(array $filters);
}
