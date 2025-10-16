<?php
// Include your database connection file
include_once 'db_connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $orderId = isset($_POST['order_id']) ? $_POST['order_id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Validate input values
    if (!empty($orderId) && !empty($status)) {
        // Prepare and execute SQL statement to update order status
        $sql = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $orderId);

        if (mysqli_stmt_execute($stmt)) {
            // Order status updated successfully
            echo json_encode(array("success" => true));
        } else {
            // Error updating order status
            echo json_encode(array("success" => false, "error" => "Failed to update order status"));
        }
    } else {
        // Invalid input parameters
        echo json_encode(array("success" => false, "error" => "Invalid input parameters"));
    }
} else {
    // Invalid request method
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}

// Close database connection
mysqli_close($conn);
?>
