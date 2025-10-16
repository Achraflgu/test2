<?php
// Include your database connection file
include 'db_connection.php';

// Check if productId is set
if(isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Delete item from the cart table based on productId
    $query = "DELETE FROM cart WHERE product_id = ? AND user_ip = ?";

    // Prepare and bind parameters
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'is', $productId, $_SERVER['REMOTE_ADDR']);

    // Execute query
    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove item']);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
