<?php

declare(strict_types=1);

namespace Src\Database;

use PDO;

class Dao extends Connection
{
    /**
     * @var PDO
     */
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Connection::getInstance();
    }

    /**
     * Get the primary key of the current table
     *
     * @return string The name of the primary key
     */
    private function getPrimaryKey(): string
    {
        $sql = 'SHOW COLUMNS FROM ' . $this->table . ' WHERE `Key` = "PRI";';
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetch()->Field;
    }

    /**
     * Get the number of columns in the current table
     *
     * @return int The number of columns
     */
    public function columnCount(): int
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->db->query($sql);
        return $sth->columnCount();
    }

    /**
     * Get the name of a column in the current table by its index
     *
     * @param int $x The index of the column
     *
     * @return string The name of the column
     */
    public function columnName(int $x): string
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->db->query($sql);
        $meta = $sth->getColumnMeta($x);
        return $meta['name'];
    }

    /**
     * Get an array of all column names in the current table
     *
     * @return array The names of all columns
     */
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

    /**
     * Select records from the current table with optional conditions
     *
     * @param array $conditions An associative array of conditions, where the key is the column name and the value is the value to search for
     *
     * @return array An array of records that match the conditions
     */
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

    /**
     * Returns the count of records in the current table
     *
     * @return int The count of records in the table
     */
    public function countRegs(): int
    {
        $pk = $this->getPrimaryKey();
        $sql = "SELECT COUNT($pk) AS soma FROM $this->table";
        $query = $this->db->prepare($sql);
        $query->execute();
        return (int)$query->fetch()->soma;
    }

    /**
     * Inserts a new record into the current table
     *
     * @param array $data An associative array with the keys as the column names and the values as the data to be inserted
     * @return bool True on success, false otherwise
     */
    public function store(array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $values = ":" . implode(', :', array_keys($data));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        $query = $this->db->prepare($sql);
        return $query->execute($data);
    }

    /**
     * Updates a record in the current table
     *
     * @param array $data An associative array with the keys as the column names and the values as the updated data
     * @param mixed $id The value of the primary key of the record to be updated
     * @return bool True on success, false otherwise
     */
    public function edit(array $data, mixed $id): bool
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
     * Deletes a record from the current table based on the primary key
     *
     * @param mixed $id The value of the primary key of the record to be deleted
     * @return bool True on success, false otherwise
     */
    public function remove(mixed $id): bool
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
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteByPk(mixed $id): bool
    {
        $pk = $this->getPrimaryKey();
        $sql = "DELETE FROM {$this->table} WHERE $pk = :field_id";
        $query = $this->db->prepare($sql);
        return $query->execute(['field_id' => $id]);
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
