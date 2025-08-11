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
     * @param string $firstname The first name of the user.
     * @param string $lastname The last name of the user.
     * @param string $email The email address of the user.
     * @param string $birthdate The birthdate of the user in 'YYYY-MM-DD' format.
     */
    public function addUser(string $firstname, string $lastname, string $email, string $birthdate): void
    {
        // Validate inputs
        if (empty($firstname) || empty($lastname) || empty($email) || empty($birthdate)) {
            throw new \InvalidArgumentException("All fields are required.");
        }
        if (!$this->validateEmail($email)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }
        if (!$this->validateDate($birthdate)) {
            throw new \InvalidArgumentException("Invalid birthdate format. Use YYYY-MM-DD.");
        }

        $sql = "INSERT INTO users (first_name, last_name, email, birth_date) VALUES (:first_name, :last_name, :email, :birth_date)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':first_name', $firstname);
            $stmt->bindParam(':last_name', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':birth_date', $birthdate);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Searches for users by first name, last name, email or birthdate.
     *
     * @param string $searchterm The search term.
     * @return array An array of matching users.
     */
    public function searchUser(string $searchterm): array
    {
        $sql = "SELECT * FROM users WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR birth_date LIKE :search";
        try {
            $stmt = $this->db->prepare($sql);
            $searchparam = '%' . $searchterm . '%';
            $stmt->bindParam(':search', $searchparam);
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
        // Basic ID validation
        if ($id <= 0) {
            throw new \InvalidArgumentException("User ID must be a positive integer.");
        }
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
     * @param string $firstname The new first name.
     * @param string $lastname The new last name.
     * @param string $email The new email.
     * @param string $birthdate The new birthdate.
     */
    public function updateUser(int $id, string $firstname, string $lastname, string $email, string $birthdate): void
    {
        // Validate inputs
        if ($id <= 0) {
            throw new \InvalidArgumentException("User ID must be a positive integer.");
        }
        if (empty($firstname) || empty($lastname) || empty($email) || empty($birthdate)) {
            throw new \InvalidArgumentException("All fields are required.");
        }
        if (!$this->validateEmail($email)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }
        if (!$this->validateDate($birthdate)) {
            throw new \InvalidArgumentException("Invalid birthdate format. Use YYYY-MM-DD.");
        }

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birth_date = :birth_date WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->bindParam(':first_name', $firstname);
            $stmt->bindParam(':last_name', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':birth_date', $birthdate);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Validates a date string in 'YYYY-MM-DD' format.
     * @param string $date The date string to validate.
     * @return bool True if the date is valid, false otherwise.
     */
    private function validateDate(string $date): bool
    {
        $dateparts = explode('-', $date);
        if (count($dateparts) !== 3) {
            return false;
        }
        return checkdate($dateparts[1], $dateparts[2], $dateparts[0]);
    }

    /**
     * Validates an email address.
     * @param string $email The email address to validate.
     * @return bool True if the email is valid, false otherwise.
     */
    private function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

}