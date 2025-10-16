<?php
// Include your database connection file
include("db_connection.php");

// Check if the product ID is provided
if(isset($_POST['productId'])) {
    // Sanitize the input to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_POST['productId']);

    // Query to fetch the product photo based on the product ID
    $sql = "SELECT product_photo FROM products WHERE product_id = $productId";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if($result) {
        // Check if any rows were returned
        if(mysqli_num_rows($result) > 0) {
            // Fetch the product photo path
            $row = mysqli_fetch_assoc($result);
            $productPhoto = $row['product_photo'];

            // Output the product photo path
            echo $productPhoto;
        } else {
            // No product photo found for the given product ID
            echo "No photo found";
        }
    } else {
        // Error in executing the query
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Product ID not provided
    echo "Product ID not specified";
}
?>
