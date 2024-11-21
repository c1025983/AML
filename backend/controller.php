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

    // Function to get the total number of new members in the past week
    public function getNewMembersPastWeek() {
        // Get the date for one week ago
        $oneWeekAgo = date('Y-m-d', strtotime('-1 week'));

        // Query to count new members who registered in the past week
        $query = "SELECT COUNT(*) AS new_members FROM librarymember WHERE registration_date >= '$oneWeekAgo'"; 
        $result = mysqli_query($this->conn, $query);

        // Fetch the result
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['new_members'];  // Return the number of new members
        } else {
            return 0;  // Return 0 if there's an error
        }
    }
}
?>
