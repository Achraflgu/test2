<?php
// Check if category ID is provided
if(isset($_POST['categoryId'])) {
    // Include your database connection file
    include 'db_connection.php';

    // Sanitize the category ID
    $categoryId = mysqli_real_escape_string($conn, $_POST['categoryId']);

    // Perform DELETE query
    $query = "DELETE FROM productcategories WHERE pcategory_id = $categoryId";
    $result = mysqli_query($conn, $query);

    // Check if deletion was successful
    if($result) {
        // Send success response
        echo json_encode(array('status' => 'success', 'message' => 'Category deleted successfully'));
    } else {
        // Send error response
        echo json_encode(array('status' => 'error', 'message' => 'Failed to delete category'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Send error response if category ID is not provided
    echo json_encode(array('status' => 'error', 'message' => 'Category ID not provided'));
}
?>
