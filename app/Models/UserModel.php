<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;
use Src\Database\ConnectionInterface;

class UserModel
{
    private const TABLE = 'user';
    private ConnectionInterface $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE;
            $stmt = $this->db->connect()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting all users' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): ?array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return empty($result) ? null : $result[0];
        } catch (PDOException $e) {
            error_log('Error getting user' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $data): void
    {
        try {
            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO " . self::TABLE . " (name, email, password)
            VALUES (:name, :email, :password)";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->execute();

        } catch (PDOException $e) {
            error_log('Error creating user in database ' . $e->getMessage());
        } catch (Exception $e) {
            error_log('Error creating user ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $data): void
    {
        try {
            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "UPDATE " . self::TABLE . " SET
            name = :name,
            email = :email,
            password = :password,
            WHERE id = :id";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error editing user' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            $query = "DELETE FROM ". self::TABLE . " WHERE id = :id";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error deleting user ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * @throws Exception
     */
    public function getByEmail(string $email): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE email = :email LIMIT 1";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                return [];
            }
            return $result;
        } catch (PDOException $e) {
            error_log('Error getting user by email ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public function getByName(string $name): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE name = :name LIMIT 1";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting user by name ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public function getCount(): int
    {
        try {
            $query = "SELECT COUNT(*) as count FROM " . self::TABLE;
            $stmt = $this->db->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log('Error getting user count ' . $e->getMessage());
            return 0;
        }
    }


    /**
     * @throws Exception
     */
    public function getPaginatedList(int $limit, int $offset): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting list of users ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public function changePassword(int $id, string $password): bool
    {
        $query = "UPDATE ". self::TABLE . " SET password = :password WHERE id = :id";
        $stmt = $this->db->connect()->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error when changing user password ' . $e->getMessage());
            return false;
        }
    }
}
