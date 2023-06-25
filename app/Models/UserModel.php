<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;
use Src\Database\ConnectionInterface;

class UserModel
{
    private ConnectionInterface $db;

    /**
     * UserModel constructor.
     *
     * @param ConnectionInterface $db The database connection
     */
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Get all users.
     *
     * @return array An array containing all users
     * @throws Exception If an error occurs while retrieving the users
     */
    public function getAll(): array
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting all users: %s', $e->getMessage()));
            return [];
        }
    }


    /**
     * Get user by ID.
     *
     * @param int $id The user ID
     * @return array|null The user data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the user
     */
    public function getById(int $id): ?array
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user by Id: %s', $e->getMessage()));
            return [];
        }
    }

    /**
     * Create a new user.
     *
     * @param array $data The user data
     * @throws Exception If an error occurs while creating the user
     */
    public function create(array $data): void
    {
        try {
            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = $this->db->connect()
                ->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :password)");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->execute();
        } catch (PDOException $e) {
            error_log(sprintf('Database error creating user: %s', $e->getMessage()));
        }
    }

    /**
     * Update a user.
     *
     * @param int $id The user ID
     * @param array $data The updated user data
     * @throws Exception If an error occurs while updating the user
     */
    public function update(int $id, array $data): void
    {
        try {
            $name = $data['name'] ?? '';
            $email = $data['email'] ?? '';
            $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';

            $query = $this->db->connect()->prepare("UPDATE user SET
                name = :name, email = :email, password = :password WHERE id = :id");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $e) {
            error_log(sprintf('Database error updating user: %s', $e->getMessage()));
        }
    }

    /**
     * Delete a user.
     *
     * @param int $id The user ID
     * @return bool True if the user was deleted successfully, false otherwise
     * @throws Exception If an error occurs while deleting the user
     */
    public function delete(int $id): bool
    {
        try {
            $query = $this->db->connect()->prepare("DELETE FROM user WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user by user id: %s', $e->getMessage()));
            return false;
        }
    }

    /**
     * Get a user by email.
     *
     * @param string $email The user email
     * @return array|null The user data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the user
     */
    public function getByEmail(string $email): array|null
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user WHERE email = :email");
            $query->bindParam(':email', $email);
            $query->execute();
            $result = $query->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user by email: %s', $e->getMessage()));
            return [];
        }
    }

    /**
     * Get a user by name.
     *
     * @param string $name The user name
     * @return array|null The user data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the user
     */
    public function getByName(string $name): ?array
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user WHERE name LIKE :name");
            $namePattern = '%' . $name . '%';
            $query->bindParam(':name', $namePattern);
            $query->execute();
            $result = $query->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user by name: %s', $e->getMessage()));
            return [];
        }
    }

    /**
     * Get a user by token.
     *
     * @param string $token The user token
     * @return array|null The user data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the user
     */
    public function getByToken(string $token): ?array
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user WHERE token = :token");
            $query->bindParam(':token', $token);
            $query->execute();
            $result = $query->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user by token: %s', $e->getMessage()));
            return null;
        }
    }

    /**
     * Get the total count of users.
     *
     * @return int The number of users
     * @throws Exception If an error occurs while retrieving the count
     */
    public function getCount(): int
    {
        try {
            $query = $this->db->connect()->prepare("SELECT COUNT(*) FROM user");
            $query->execute();
            $result = $query->fetchColumn();

            return $result ?: 0;
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting user count: %s', $e->getMessage()));
            return 0;
        }
    }

    /**
     * Get a paginated list of users.
     *
     * @param int $limit The number of users to retrieve per page
     * @param int $offset The offset for pagination
     * @return array An array containing the paginated list of users
     * @throws Exception If an error occurs while retrieving the list
     */
    public function getPaginatedList(int $limit, int $offset): array
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM user LIMIT :limit OFFSET :offset");
            $query->bindParam(':limit', $limit, PDO::PARAM_INT);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll();

            return $result ?: [];
        } catch (PDOException $e) {
            error_log(sprintf('Database error on getting paginated list of users: %s', $e->getMessage()));
            return [];
        }
    }

    /**
     * Set the token for a user.
     *
     * @param int $id The user ID
     * @param string $token The token to set
     * @return bool True if the token was set successfully, false otherwise
     * @throws Exception If an error occurs while updating the token
     */
    public function setToken(int $id, string $token): bool
    {
        try {
            $statement = $this->db->connect()->prepare("UPDATE user SET token = :token WHERE id = :id");
            $statement->bindParam(":token", $token);
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log(sprintf('Database error updating token of user: %s', $e->getMessage()));
            return false;
        }
    }

    /**
     * Change the password for a user.
     *
     * @param int $id The user ID
     * @param string $password The new password
     * @return bool True if the password was changed successfully, false otherwise
     * @throws Exception If an error occurs while updating the password
     */
    public function changePassword(int $id, string $password): bool
    {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $statement = $this->db->connect()->prepare("UPDATE user SET password = :passwordHash WHERE id = :id");
            $statement->bindParam(":passwordHash", $passwordHash);
            $statement->bindParam(":id", $id);
            $statement->execute();

            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            error_log(sprintf('Database error updating user: %s', $e->getMessage()));
            return false;
        }
    }
}
