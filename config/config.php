<?php
if (!isset($_SESSION)) {
    session_start();
}

class dbClass
{
    private $host = 'localhost';
    private $dbname = 'orbitstarservices_db';
    private $user = 'orbitstarservices_db';
    private $pass = 'bzTs9UU4kT2kCFRdMnrB';

    public $conn;
    public $error;

    public function __construct()
    {
        $this->connect();
        $this->autoInstall();
    }

    private function connect()
    {
        // Credentials to try
        $credentials = [
            ['username' => $this->user, 'password' => $this->pass],
            ['username' => 'root', 'password' => ''],
            ['username' => 'root', 'password' => 'root']
        ];

        // 1. Try direct connection to database
        foreach ($credentials as $cred) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $cred['username'],
                    $cred['password']
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->user = $cred['username'];
                $this->pass = $cred['password'];
                return;
            } catch (PDOException $e) {
                // Keep trying
            }
        }

        // 2. Try creating the database if connection failed (for localhost)
        foreach ($credentials as $cred) {
            try {
                $temp_conn = new PDO("mysql:host={$this->host};charset=utf8mb4", $cred['username'], $cred['password']);
                $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                $temp_conn = null;
                
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $cred['username'],
                    $cred['password']
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->user = $cred['username'];
                $this->pass = $cred['password'];
                return;
            } catch (PDOException $e) {
                // Keep trying
            }
        }

        die("❌ Database connection failed. Please check credentials.");
    }

    private function autoInstall()
    {
        try {
            // Create admin table
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `admin` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `email` varchar(100) NOT NULL,
              `username` varchar(100) NOT NULL,
              `password` varchar(255) NOT NULL,
              `phone` varchar(50) DEFAULT NULL,
              `image` varchar(255) DEFAULT NULL,
              `login_ip` varchar(50) DEFAULT NULL,
              `login_date` datetime DEFAULT NULL,
              `date` date NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Add missing columns to existing admin tables (from old system)
            $missingColumns = [
                'login_ip'   => "ALTER TABLE `admin` ADD COLUMN `login_ip` varchar(50) DEFAULT NULL",
                'login_date' => "ALTER TABLE `admin` ADD COLUMN `login_date` datetime DEFAULT NULL",
                'phone'      => "ALTER TABLE `admin` ADD COLUMN `phone` varchar(50) DEFAULT NULL",
                'image'      => "ALTER TABLE `admin` ADD COLUMN `image` varchar(255) DEFAULT NULL",
                'username'   => "ALTER TABLE `admin` ADD COLUMN `username` varchar(100) NOT NULL DEFAULT ''",
            ];
            foreach ($missingColumns as $col => $sql) {
                try {
                    $check = $this->conn->query("SHOW COLUMNS FROM `admin` LIKE '$col'");
                    if ($check->rowCount() === 0) {
                        $this->conn->exec($sql);
                    }
                } catch (PDOException $e) {
                    // Column may already exist, ignore
                }
            }


            // Seed admin account - create if missing
            $stmt = $this->conn->prepare("SELECT id, password FROM `admin` WHERE `email` = 'admin@gmail.com'");
            $stmt->execute();
            $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingAdmin) {
                // Create fresh admin with hashed password
                $hashedPass = password_hash('13579', PASSWORD_DEFAULT);
                $this->conn->exec("INSERT INTO `admin` (`id`, `email`, `username`, `password`, `date`) VALUES (1, 'admin@gmail.com', 'admin@gmail.com', '$hashedPass', CURRENT_DATE())");
            } else {
                // Upgrade plain-text password to bcrypt if needed
                $storedPass = $existingAdmin['password'];
                $isHashed = (strlen($storedPass) >= 60 && substr($storedPass, 0, 1) === '$');
                if (!$isHashed) {
                    $hashedPass = password_hash($storedPass, PASSWORD_DEFAULT);
                    $this->conn->exec("UPDATE `admin` SET `password` = '$hashedPass' WHERE `email` = 'admin@gmail.com'");
                }
            }

            // Create contact_us table
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `contact_us` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) NOT NULL,
              `email` varchar(100) NOT NULL,
              `phone` varchar(100) NOT NULL,
              `subject` text NOT NULL,
              `message` text NOT NULL,
              `date` date NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Create quote table
            $this->conn->exec("CREATE TABLE IF NOT EXISTS `quote` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) NOT NULL,
              `email` varchar(100) NOT NULL,
              `phone` varchar(100) NOT NULL,
              `code` varchar(100) NOT NULL,
              `services` varchar(100) NOT NULL,
              `message` text DEFAULT NULL,
              `date` date NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        } catch (PDOException $e) {
            // Ignore/Log
        }
    }

    public function getData($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("❌ SQL Error: " . $e->getMessage());
        }
    }

    public function executeStatement($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("SQL Error: " . $e->getMessage());
        }
    }

    public function getAllData($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("❌ SQL Error: " . $e->getMessage());
        }
    }

    public function insertData($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("❌ Insert Error: " . $e->getMessage());
        }
    }

    public function updateData($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("❌ Update Error: " . $e->getMessage());
        }
    }

    public function deleteData($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("❌ Delete Error: " . $e->getMessage());
        }
    }
}

// Time settings
date_default_timezone_set("Asia/Dubai");
$dateTime = date('Y-m-d H:i:s');
$date = date('Y-m-d');
$time = date('H:i:s');
?>
