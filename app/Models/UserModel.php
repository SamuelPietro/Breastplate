<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;
use Src\Core\WebHelper;
use Src\Database\Connection;

/**
 * UserModel class.
 */
class UserModel extends Connection
{
    private const TABLE = 'users';
    private PDO $db;
    private WebHelper $webHelper;
    
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = Connection::getInstance();
        $this->webHelper = new WebHelper();
    }

    /**
     * Get all users from the database
     *
     * @return array
     * @throws Exception If an error occurs while fetching the users
     */
    public function getAll(): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE;
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error while getting users: " . $e->getMessage());
        }
    }

    /**
     * Get a user by ID
     *
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public function getById(int $id): ?array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return empty($result) ? null : $result[0];
        } catch (PDOException $e) {
            throw new Exception("Error while getting user: " . $e->getMessage());
        }
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @throws Exception
     */
    public function create(array $data): void
    {
        try {
            if (!$this->webHelper->verifyCsrfToken($data['csrf_token'])) {
                throw new Exception('Invalid CSRF token.');
            }

            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            print_r($data);
            $query = "INSERT INTO " . self::TABLE . " (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->execute();
           
        } catch (PDOException $e) {
            throw new Exception("Error while creating user: " . $e->getMessage());
        }
    }

    /**
     * Update a user by ID
     *
     * @param int $id
     * @param array $data
     * @throws Exception
     */
    public function update(int $id, array $data): void
    {
        try {
            if (!$this->webHelper->verifyCsrfToken($data['csrf_token'])) {
                throw new Exception('Invalid CSRF token.');
            }

            $name = $data['name'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);

            $query = "UPDATE " . self::TABLE . " SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error while editing user: " . $e->getMessage());
        }
    }

    /**
     * Delete a user from the database.
     *
     * @param int $id User id to be deleted.
     *
     * @return bool True if the user was successfully deleted, false otherwise.
     *
     * @throws Exception If an error occurs during the deletion process.
     */
    public function delete(int $id, $data): bool
    {
        try {
            // Verify CSRF token
            if (!$this->webHelper->verifyCsrfToken($data['csrf_token'])) {
                throw new Exception('Invalid CSRF token.');
            }

            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error while deleting user: " . $e->getMessage());
        }
    }

    /**
     * Get a user by email
     *
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function getByEmail(string $email): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error while getting user by email: " . $e->getMessage());
        }
    }

    /**
     * Get a user by name
     *
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function getByName(string $name): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " WHERE name = :name LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error while getting user by name: " . $e->getMessage());
        }
    }

    /**
     * Get the total count of users
     *
     * @return int
     * @throws Exception
     */
    public function getCount(): int
    {
        try {
            $query = "SELECT COUNT(*) as count FROM " . self::TABLE;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            throw new Exception("Error while getting user count: " . $e->getMessage());
        }
    }

    /**
     * Get a paginated list of users
     *
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getPaginatedList(int $limit, int $offset): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE . " LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error while getting user list: " . $e->getMessage());
        }
    }

    /**
     * Change the password of the user with the given ID
     *
     * @param int $id The user ID
     * @param string $password The new password for the user
     * @return bool True if the password was changed successfully, false otherwise
     * @throws Exception If there is an error changing the password
     */
    public function changePassword(int $id, string $password): bool
    {
        $csrfToken = $this->webHelper->getCsrfToken();
        if (!$this->webHelper->verifyCsrfToken($csrfToken)) {
            throw new Exception("Invalid CSRF token");
        }

        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error changing user password: " . $e->getMessage());
        }
    }
}
