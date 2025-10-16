<?php
// Include database connection
include_once 'db_connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the blog ID and status are set
    if (isset($_POST['blog_id']) && isset($_POST['status'])) {
        // Sanitize input
        $blogId = mysqli_real_escape_string($conn, $_POST['blog_id']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        // Update the status in the database
        $sql = "UPDATE blog SET status='$status' WHERE blog_id=$blogId";

        if (mysqli_query($conn, $sql)) {
            echo "Status updated successfully";
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid request";
    }
} else {
    echo "Method not allowed";
}
?>
