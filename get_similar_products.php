<?php
include("db_connection.php");

$productName = $_GET['product_name'];
$currentProductId = isset($_GET['current_product_id']) ? $_GET['current_product_id'] : null;

// Prepare the query to fetch similar products excluding the current one
$query = "SELECT * FROM products WHERE product_name = ?";
if ($currentProductId !== null) {
    $query .= " AND product_id != ?";
}

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind parameters
if ($currentProductId !== null) {
    $stmt->bind_param('si', $productName, $currentProductId);
} else {
    $stmt->bind_param('s', $productName);
}

// Execute the query
$stmt->execute();

// Get the results
$results = $stmt->get_result();

// Fetch the results as an associative array
$similarProducts = $results->fetch_all(MYSQLI_ASSOC);

// Convert the results to JSON and output
echo json_encode($similarProducts);
?>