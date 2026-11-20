<?php
/**
 * Prime Fleet Services - Database Connection Layer
 */

require_once __DIR__ . '/config.php';

function get_db_connection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_DRIVER === 'sqlite') {
            $dbFile = SQLITE_PATH;
            $dbDir = dirname($dbFile);
            if (!is_dir($dbDir)) {
                mkdir($dbDir, 0777, true);
            }
            $isNew = !file_exists($dbFile) || filesize($dbFile) === 0;
            $pdo = new PDO("sqlite:" . $dbFile, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 30
            ]);
            $pdo->exec("PRAGMA journal_mode = WAL;");
            $pdo->exec("PRAGMA foreign_keys = ON;");

            if ($isNew) {
                require_once __DIR__ . '/seed.php';
                init_sqlite_database($pdo);
            }
        } else {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        }
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    return $pdo;
}

$pdo = get_db_connection();
