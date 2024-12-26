<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnected();
    }

    /**
     * Register a new user.
     */
    public function register($username, $password, $email, $phone, $firstname = "", $lastname = "", $birthdate = null, $gender = null, $role = 'user', $status = 'active') {
        try {
            $query = "INSERT INTO " . $this->table . " (firstname, lastname, phone, username, email, password, birthdate, gender, role, status) 
                      VALUES (:firstname, :lastname, :phone, :username, :email, :password, :birthdate, :gender, :role, :status)";

            $stmt = $this->conn->prepare($query);

            // Hash the password securely
            $hashedPass = password_hash($password, PASSWORD_BCRYPT);

            // Bind parameters
            $stmt->bindParam(":firstname", $firstname);
            $stmt->bindParam(":lastname", $lastname);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedPass);
            $stmt->bindParam(":birthdate", $birthdate);
            $stmt->bindParam(":gender", $gender);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":status", $status);

            // Execute and return success status
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Registration failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Login a user.
     */
    public function login($usernameOrEmail, $password) {
        try {
            // Determine if the input is an email
            $isEmail = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL);
    
            $query = $isEmail
                ? "SELECT id, username, role, password FROM " . $this->table . " WHERE email = :identifier AND status = 'active'"
                : "SELECT id, username, role, password FROM " . $this->table . " WHERE username = :identifier AND status = 'active'";
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":identifier", $usernameOrEmail);
            $stmt->execute();
    
            // Fetch user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                return false; // User not found or inactive
            }
    
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Return user data on successful login
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
            }
    
            return false; // Password incorrect
        } catch (PDOException $e) {
            error_log("Login failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Store a "Remember Me" token for persistent login.
     */
    public function storeRememberToken($usernameOrEmail, $token) {
        try {
            $query = "UPDATE " . $this->table . " SET remember_token = :token WHERE email = :identifier OR username = :identifier";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":identifier", $usernameOrEmail);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Storing remember token failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate a "Remember Me" token for persistent login.
     */
    public function validateRememberToken($token) {
        try {
            $query = "SELECT id, username, role FROM " . $this->table . " WHERE remember_token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Set session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Validating remember token failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update the last login timestamp.
     */
    private function updateLastLogin($userId) {
        try {
            $query = "UPDATE " . $this->table . " SET last_login = CURRENT_TIMESTAMP WHERE id = :userId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":userId", $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Updating last login failed: " . $e->getMessage());
        }
    }

    /**
 * Check if an email exists in the database.
 */
public function emailExists($email) {
    try {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Email existence check failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Store a password reset token for the user.
 */
public function storeResetToken($email, $token) {
    try {
        $query = "UPDATE " . $this->table . " SET reset_token = :token, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Storing reset token failed: " . $e->getMessage());
        return false;
    }
}

}
