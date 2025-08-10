<?php

namespace users;

class User
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Adds a new user to the database.
     *
     * @param string $first_name The first name of the user.
     * @param string $last_name The last name of the user.
     * @param string $email The email address of the user.
     * @param string $birthdate The birthdate of the user in 'YYYY-MM-DD' format.
     */
    public function addUser(string $first_name, string $last_name, string $email, string $birthdate): void
    {
        $sql = "INSERT INTO users (first_name, last_name, email, birth_date) VALUES (:first_name, :last_name, :email, :birth_date)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':birth_date', $birthdate);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Searches for users by first name, last name, or email.
     *
     * @param string $search_term The search term.
     * @return array An array of matching users.
     */
    public function searchUser(string $search_term): array
    {
        $sql = "SELECT * FROM users WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search";
        try {
            $stmt = $this->db->prepare($sql);
            $searchParam = '%' . $search_term . '%';
            $stmt->bindParam(':search', $searchParam);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Deletes a user by their ID.
     *
     * @param int $id The ID of the user to delete.
     */
    public function deleteUser(int $id): void
    {
        $sql = "DELETE FROM users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Updates a user's information.
     *
     * @param int $id The ID of the user to update.
     * @param string $first_name The new first name.
     * @param string $last_name The new last name.
     * @param string $email The new email.
     * @param string $birthdate The new birthdate.
     */
    public function updateUser(int $id, string $first_name, string $last_name, string $email, string $birthdate): void
    {
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birth_date = :birth_date WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':birth_date', $birthdate);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}