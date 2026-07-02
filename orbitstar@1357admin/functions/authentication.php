<?php
class AdminManager
{
    private $conn;
    private const UPLOAD_DIR_ABSOLUTE = __DIR__ . '/../images/'; 


    public function __construct()
    {
        $this->conn = new dbClass();
    }

    public function adminLogin($email, $pass)
    {
        $result = $this->conn->getData("SELECT * FROM `admin` WHERE `email` = :email", [':email' => $email]);

        if (!$result) {
            return false;
        }

        $storedPassword = $result['password'];

        // Support both old plain-text passwords and new bcrypt hashed passwords
        $isHashedPassword = (strlen($storedPassword) >= 60 && substr($storedPassword, 0, 1) === '$');

        if ($isHashedPassword) {
            // New bcrypt comparison
            $passwordMatch = password_verify($pass, $storedPassword);
        } else {
            // Old plain-text comparison (exact match - same as old system)
            $passwordMatch = ($storedPassword === $pass);
        }

        if ($passwordMatch) {
            // Auto-upgrade plain-text password to bcrypt for security
            if (!$isHashedPassword) {
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $this->conn->executeStatement(
                    "UPDATE `admin` SET `password` = :password WHERE `id` = :id",
                    [':password' => $newHash, ':id' => $result['id']]
                );
            }

            // Update login tracking
            $query = "UPDATE `admin` SET `login_ip` = :login_ip, `login_date` = NOW() WHERE `id` = :id";
            $this->conn->executeStatement($query, [':login_ip' => $_SERVER['REMOTE_ADDR'], ':id' => $result['id']]);

            $_SESSION['ADMIN_USER_ID'] = $result['id'];

            return true;
        }

        return false;
    }

    public function checkSession()
    {
        if (!isset($_SESSION['ADMIN_USER_ID']) || empty($_SESSION['ADMIN_USER_ID'])) {
            header('Location: index.php');
            exit();
        }
    }

    public function signOut()
    {
        unset($_SESSION['ADMIN_USER_ID']);
        session_destroy();
        header('Location: index.php');
        exit();
    }

    public function getAdminHeaderInfo($adminId)
    {
        if (!$adminId) {
            return null;
        }
        return $this->conn->getData("SELECT username, email, image FROM admin WHERE id = :id", [':id' => $adminId]);
    }

    public function getAdminDetails($adminId)
    {
        if (!$adminId) {
            return false;
        }
        return $this->conn->getData("SELECT * FROM `admin` WHERE `id` = :id", [':id' => $adminId]);
    }

    public function updateProfileDetails($adminId, $data, $file)
    {
        $username = trim($data['username']);
        $phone = trim($data['phone']);
        $email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
        $oldImageFilename = $data['oldimage'] ?? null;
        $newImageFilename = $oldImageFilename;

        if (empty($username) || empty($phone) || empty($email)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            if (!empty($oldImageFilename)) {
                $oldImageFullPath = self::UPLOAD_DIR_ABSOLUTE . $oldImageFilename;

                if (file_exists($oldImageFullPath)) {
                    @unlink($oldImageFullPath);
                }
            }

            $uploadResult = uploadFileHandler($file, null, self::UPLOAD_DIR_ABSOLUTE);

            if (is_string($uploadResult) && strpos($uploadResult, 'ERROR:') === 0) {
                return ['success' => false, 'message' => $uploadResult];
            }
            $newImageFilename = $uploadResult;
        }

        $sql = "UPDATE admin SET username = :username, phone = :phone, email = :email, image = :image WHERE id = :id";
        $params = [
            ':username' => $username,
            ':phone' => $phone,
            ':email' => $email,
            ':image' => $newImageFilename,
            ':id' => $adminId
        ];

        if ($this->conn->executeStatement($sql, $params)) {
            return [
                'success' => true,
                'message' => 'Profile updated successfully!',
                'newData' => [
                    'username' => $username,
                    'email' => $email,
                    'image' => $newImageFilename
                ]
            ];
        }

        return ['success' => false, 'message' => 'Failed to update profile.'];
    }




    public function changePassword($adminId, $currentPassword, $newPassword)
    {
        if (empty($currentPassword) || empty($newPassword)) {
            return ['success' => false, 'message' => 'All password fields are required.'];
        }
        $admin = $this->getAdminDetails($adminId);
        if (!$admin) {
            return ['success' => false, 'message' => 'Could not find user.'];
        }

        if (!password_verify($currentPassword, $admin['password'])) {
            return ['success' => false, 'message' => 'Your current password is incorrect.'];
        }

        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE admin SET password = :password WHERE id = :id";
        if ($this->conn->executeStatement($sql, [':password' => $newHashedPassword, ':id' => $adminId])) {
            return ['success' => true, 'message' => 'Password changed successfully!'];
        }
        return ['success' => false, 'message' => 'An error occurred while changing the password.'];
    }
}

?>