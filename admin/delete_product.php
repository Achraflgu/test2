<?php
// Check if the product ID is provided
if (isset($_POST['productId'])) {
    // Include your database connection file
    include 'db_connection.php';

    // Sanitize the input to prevent SQL injection
    $productId = filter_var($_POST['productId'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare and execute the DELETE query for associated records in the orderitems table
    $deleteOrderItemsSql = "DELETE FROM orderitems WHERE product_id = ?";
    $stmtOrderItems = $conn->prepare($deleteOrderItemsSql);
    $stmtOrderItems->bind_param("i", $productId);
    $stmtOrderItems->execute();

    // Prepare and execute the DELETE query for the product
    $deleteProductSql = "DELETE FROM products WHERE product_id = ?";
    $stmtProduct = $conn->prepare($deleteProductSql);
    $stmtProduct->bind_param("i", $productId);
    $stmtProduct->execute();

    // Check if the deletion was successful
    if ($stmtProduct->affected_rows > 0) {
        // Product deletion successful
        $response = array('success' => true, 'message' => 'Product deleted successfully');
    } else {
        // Product deletion failed
        $response = array('success' => false, 'message' => 'Failed to delete product');
    }

    // Close the prepared statements
    $stmtOrderItems->close();
    $stmtProduct->close();

    // Close the database connection
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Ensure no further output is sent
} else {
    // If product ID is not provided
    $response = array('success' => false, 'message' => 'Product ID not provided');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Ensure no further output is sent
}
?>
