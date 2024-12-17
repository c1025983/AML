<?php
class LibraryModel {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct() {
        // Include the config file for DB connection
        require_once('config.php');
        
        // Initialize the connection variable
        $this->conn = $conn;  // Use the $conn variable from config.php
    }

    // Function to get the total number of members
    public function getTotalMembers() {
        $query = "SELECT COUNT(*) AS total FROM librarymember";  // Query to count members
        $result = mysqli_query($this->conn, $query);

        // Fetch the result
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];  // Return the total number of members
        } else {
            return 0;  // Return 0 if there's an error
        }
    }
}
?>
