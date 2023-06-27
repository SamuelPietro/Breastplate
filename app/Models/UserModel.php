<?php

namespace App\Models;

use App\Interfaces\CRUDInterface;
use Exception;
use PDO;
use PDOException;
use Src\Database\ConnectionInterface;

class UserModel implements CRUDInterface
{
    private ConnectionInterface $connection;

    /**
     * UserModel constructor.
     *
     * @param ConnectionInterface $connection The database connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
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
            $query = $this->connection->connect()->prepare("SELECT * FROM user");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log(sprintf('Database error on getting all users: %s', $exception->getMessage()));
            return [];
        }
    }

    /**
     * Get user by field.
     *
     * @param string $field The field name to search by (e.g., 'id', 'email', 'name', 'token')
     * @param string $value The value to search for in the specified field
     * @return array|null The user data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the user
     */
    public function getByField(string $field, string $value): ?array
    {
        try {
            $query = $this->connection->connect()->prepare("SELECT * FROM user WHERE $field = :value");
            $query->bindParam(':value', $value);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log(sprintf('Database error on getting user by %s: %s', $field, $exception->getMessage()));
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
            $query = $this->connection->connect()
                ->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :password)");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->execute();
        } catch (PDOException $exception) {
            error_log(sprintf('Database error creating user: %s', $exception->getMessage()));
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

            $query = $this->connection->connect()->prepare("UPDATE user SET
                name = :name, email = :email, password = :password WHERE id = :id");
            $query->bindParam(':name', $name);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (PDOException $exception) {
            error_log(sprintf('Database error updating user: %s', $exception->getMessage()));
        }
    }

    /**
     * Delete a user.
     *
     * @param int $id The user ID
     * @return bool True if the user is deleted, false otherwise
     * @throws Exception If an error occurs while deleting the user
     */
    public function delete(int $id): bool
    {
        try {
            $query = $this->connection->connect()->prepare("DELETE FROM user WHERE id = :id");
            $query->bindParam(':id', $id);
            $query->execute();
            return $query->rowCount() > 0;
        } catch (PDOException $exception) {
            error_log(sprintf('Database error deleting user: %s', $exception->getMessage()));
            return false;
        }
    }

    /**
     * Get the total number of users.
     *
     * @return int The total number of users
     * @throws Exception If an error occurs while retrieving the count
     */
    public function getCount(): int
    {
        try {
            $query = $this->connection->connect()->prepare("SELECT COUNT(*) as total FROM user");
            $query->execute();
            return (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $exception) {
            error_log(sprintf('Database error on getting user count: %s', $exception->getMessage()));
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
            $query = $this->connection->connect()->prepare("SELECT * FROM user LIMIT :limit OFFSET :offset");
            $query->bindParam(':limit', $limit, PDO::PARAM_INT);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll();

            return $result ?: [];
        } catch (PDOException $exception) {
            error_log(sprintf('Database error on getting paginated list of users: %s', $exception->getMessage()));
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
            $statement = $this->connection->connect()->prepare("UPDATE user SET token = :token WHERE id = :id");
            $statement->bindParam(":token", $token);
            $statement->bindParam(":id", $id);
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $exception) {
            error_log(sprintf('Database error updating token of user: %s', $exception->getMessage()));
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
            $statement = $this->connection->connect()->prepare("UPDATE user SET password = :passwordHash WHERE id = :id");
            $statement->bindParam(":passwordHash", $passwordHash);
            $statement->bindParam(":id", $id);
            $statement->execute();

            return $statement->rowCount() > 0;
        } catch (PDOException $exception) {
            error_log(sprintf('Database error updating user: %s', $exception->getMessage()));
            return false;
        }
    }
}
