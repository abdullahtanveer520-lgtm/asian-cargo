<?php
/**
 * Database connection
 * Update these 4 values to match your hosting's MySQL details.
 * On most shared hosting (Hostinger, cPanel, etc.) you'll find these
 * in your hosting control panel under "MySQL Databases".
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'asian_cargo');
define('DB_USER', 'root');
define('DB_PASS', '');
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die('Database connection failed. Please check config/database.php — ' . $e->getMessage());
        }
    }

    return $pdo;
}
