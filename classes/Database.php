<?php
class Database
{
    private $host=DB_HOST;
    private $user=DB_USER;
    private $password=DB_PASS;
    private $db_name=DB_NAME;

    public $conn;

    public function getConnected()
    {
$this->conn = null;
try {
    // Correct syntax for creating a new PDO instance
    $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->user, $this->password);
    
    // Set error mode to exception for better error handling
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    // Catch and display any connection errors
    echo "CONNECTION ERROR: SAFDAR, YOU AREN'T CONNECTED TO DB - " . $exception->getMessage();
}
return $this->conn;

        
    }   

}