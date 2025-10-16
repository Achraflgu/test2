<?php
// Check if the blog ID is set and not empty
if (isset($_POST['blog_id']) && !empty($_POST['blog_id'])) {
    // Include database connection
    include_once 'db_connection.php';

    // Get the blog ID from the POST data
    $blogId = $_POST['blog_id'];

    // Prepare and execute the SQL query to delete the blog post
    $sql = "DELETE FROM blog WHERE blog_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $blogId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Deletion successful
        echo "Blog post deleted successfully.";
    } else {
        // Error occurred
        echo "Error deleting blog post: " . mysqli_error($conn);
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Blog ID not provided or invalid
    echo "Invalid request.";
}
?>
