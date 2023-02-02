<?php

namespace App\Models;

use Src\Database\Dao;


class UsersModel extends Dao
{
    protected string $table = 'users';

    public function getAll(): array
    {
        return $this->select();
    }

    public function getById(int $id): array
    {
        return $this->select(['id' => $id])[0];
    }

    public function create(array $data): void
    {
        $this->store($data);
    }

    public function update(array $data, $id)
    {
        $this->edit($data, ['id' => $id]);
    }

    public function delete($id)
    {
        $this->remove(['id' => $id]);
    }
}
