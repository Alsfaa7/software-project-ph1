

<?php
session_start(); 
require_once __DIR__ . '/model.php';


class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function isUserExists($username, $email) {
        $query = "SELECT * FROM Users WHERE Username = ? OR Email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function createUser($username, $email, $password, $userTypeId) {
        $query = "INSERT INTO Users (Username, Email, Password, UserTypeId) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $password, $userTypeId);
        $success = $stmt->execute();
        $userId = $stmt->insert_id;
        $stmt->close();
        return $success ? $userId : false;
    }
}




class UserAuthentication  {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function authenticate($email, $password) {
        $user = $this->getUserByEmail($email);

        if ($user && $this->verifyPassword($password, $user['Password'])) {
            $this->initializeSession($user);
            $this->redirectUser($user['UserTypeId']);
        } else {
            return "Invalid email or password.";
        }
    }

    private function getUserByEmail($email) {
        $query = "SELECT * FROM Users WHERE Email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    private function verifyPassword($inputPassword, $storedPassword) {
        return password_verify($inputPassword, $storedPassword);
    }

    private function initializeSession($user) {
        $_SESSION['id'] = $user['Id'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['userTypeId'] = $user['UserTypeId'];
    }

    private function redirectUser($userTypeId) {
        switch ($userTypeId) {
            case 1:
                $this->redirect("../app/views/admin.php");
                break;
            case 2:
                $this->redirect("../app/views/user.php");
                break;
            default:
                exit("Invalid user type.");
        }
    }

    private function redirect($url) {
        echo "<script>window.location.href = '$url';</script>";
        exit();
    }
    
}


$conn->close();
?>