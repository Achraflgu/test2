<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $brandId = $_POST['brandId'];
    
    // Delete brand from the database
    $delete_query = "DELETE FROM brands WHERE brand_id='$brandId'";
    if (mysqli_query($conn, $delete_query)) {
        echo "Brand deleted successfully";
    } else {
        echo "Error deleting brand: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
