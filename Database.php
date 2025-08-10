<?php

namespace database;
require_once __DIR__ . "/../config/config.php";
class Database
{
    private \PDO $pdo;

    public function __construct()
    {
        $servername = DB_HOST;
        $username = DB_USER;
        $userpass = DB_PASS;
        $dbname = DB_NAME;
        $charset = "utf8mb4";

        $dsn = "mysql:host=" . $servername . "; dbname=" . $dbname . "; charset=" . $charset;
        try {
            $pdo = new \PDO($dsn, $username, $userpass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        } catch (\PDOException $err) {
            // Re-throw the exception so it can be caught in the main script
            throw new \PDOException("Connection failed: " . $err->getMessage(), (int)$err->getCode());
        }
    }

    /**
     * Returns the PDO instance.
     *
     * @return \PDO The PDO instance.
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
