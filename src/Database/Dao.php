<?php

declare(strict_types=1);

namespace Src\Database;

use PDO;

class Dao extends Connection
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = Connection::getInstance();
    }

    private function getPrimaryKey(): string
    {
        $sql = 'SHOW COLUMNS FROM ' . $this->table . ' WHERE `Key` = "PRI";';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch()->Field;
    }

    public function columnCount(): int
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->db->query($sql);
        return $sth->columnCount();
    }

    public function columnName(int $x): string
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->db->query($sql);
        $meta = $sth->getColumnMeta($x);
        return $meta['name'];
    }
    
    public function allColumns(): array
    {
        $fld = '';
        for ($x = 1; $x < $this->columnCount(); $x++) {
            $field = $this->columnName($x);
            if ($x < $this->columnCount() - 1) {
                $fld .= $field . ',';
            } else {
                $fld .= $field;
            }
        }

        return explode(',', $fld);
    }

    public function select(array $conditions = []): array
    {
        $sql = "SELECT * FROM $this->table";
        $params = [];
        if (!empty($conditions)) {
            $sql .= ' WHERE ';
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "$column = ?";
                $params[] = $value;
            }
            $sql .= implode(' AND ', $where);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function countRegs(): int
    {
        $pk = $this->getPrimaryKey();
        $sql = "SELECT COUNT($pk) AS soma FROM $this->table";
        $query = $this->db->prepare($sql);
        $query->execute();
        return (int)$query->fetch()->soma;
    }

    public function store(array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $values = ":" . implode(', :', array_keys($data));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        $query = $this->db->prepare($sql);
        return $query->execute($data);
    }

    /**
     * Atualiza um registro na tabela atual
     */
    public function edit(array $data, $id): bool
    {
        $pk = $this->getPrimaryKey();
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');

        $sql = "UPDATE $this->table SET $set WHERE $pk = :id";
        $query = $this->db->prepare($sql);
        $data['id'] = $id;
        return $query->execute($data);
    }

    /**
     * Deleta um registro da tabela atual com base na chave primária
     */
    public function remove($id): bool
    {
        $pk = $this->getPrimaryKey();
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE $pk = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Deletes a record from the database based on the primary key
     *
     * @param mixed $id The value of the primary key
     *
     * @return void
     */
    public function deleteByPk(mixed $id): void
    {
        $pk = $this->getPrimaryKey();
        $sql = "DELETE FROM {$this->table} WHERE $pk = :field_id";
        $query = $this->db->prepare($sql);
        $parameters = [':field_id' => $id];
        $query->execute($parameters);
    }

    /**
     * Counts the total number of records in the table
     *
     * @return int The number of records
     */
    public function countRecords(): int
    {
        $pk = $this->getPrimaryKey();
        $sql = "SELECT COUNT($pk) AS total FROM {$this->table}";
        $query = $this->db->prepare($sql);
        $query->execute();
        return (int)$query->fetch()->total;
    }


}
