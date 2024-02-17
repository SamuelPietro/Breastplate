<?php

namespace pFrame\App\Interfaces;

use Exception;

/**
 * Interface for common CRUD operations.
 */
interface CRUDInterface
{
    /**
     * Get all data.
     *
     * @return array An array containing all rows
     * @throws Exception If an error occurs while retrieving the data
     */
    public function getAll(): array;

    /**
     * Get row by field.
     *
     * @param string $field The field name to search by (e.g., 'id', 'email', 'name', 'token')
     * @param string $value The value to search for in the specified field
     * @return array|null The data as an associative array, or null if not found
     * @throws Exception If an error occurs while retrieving the data
     */
    public function getByField(string $field, string $value): ?array;

    /**
     * Create a new data.
     *
     * @param array $data The data
     * @throws Exception If an error occurs while creating the data
     */
    public function create(array $data): void;

    /**
     * Update a row.
     *
     * @param int $id The data ID
     * @param array $data The updated row data
     * @throws Exception If an error occurs while updating the data
     */
    public function update(int $id, array $data): void;

    /**
     * Delete a row.
     *
     * @param int $id The row ID
     * @return bool True if the row is deleted, false otherwise
     * @throws Exception If an error occurs while deleting the data
     */
    public function delete(int $id): bool;

    /**
     * Get the total number of rows.
     *
     * @return int The total number of rows
     * @throws Exception If an error occurs while retrieving the count
     */
    public function getCount(): int;

    /**
     * Get a paginated list of data.
     *
     * @param int $limit The number of rows to retrieve per page
     * @param int $offset The offset for pagination
     * @return array An array containing the paginated list of data
     * @throws Exception If an error occurs while retrieving the list
     */
    public function getPaginatedList(int $limit, int $offset): array;
}
