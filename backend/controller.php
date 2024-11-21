<?php
// Include the model file
require_once('model.php');  // Correct path to backend/model.php

class LibraryController {
    private $model;

    // Constructor to initialize the model
    public function __construct() {
        $this->model = new LibraryModel();  // Initialize the model class
    }

    // Function to fetch the total number of library members
    public function displayTotalMembers() {
        // Get the total number of members from the model
        $totalMembers = $this->model->getTotalMembers();
        
        // Return the total members count
        return $totalMembers;
    }
}
?>
