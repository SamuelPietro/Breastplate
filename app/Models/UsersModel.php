<?php

namespace App\Models;

use Src\Database\Dao;


class UsersModel extends Dao
{
    /**
     * Table name in the database
     */
    protected string $table = 'users';

    /**
     * Get all the users from the database
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->select();
    }

    /**
     * Get user by their ID
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->select(['id' => $id])[0];
    }

    /**
     * Insert a new user into the database
     *
     * @param array $data
     */
    public function create(array $data): void
    {
        $this->store($data);
    }

    /**
     * Update an existing user in the database
     *
     * @param array $data
     * @param int $id
     */
    public function update(array $data, int $id)
    {
        $this->edit($data, ['id' => $id]);
    }

    /**
     * Delete a user from the database
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->remove(['id' => $id]);
    }
}
