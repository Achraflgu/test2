<?php
// Include database connection
include_once 'db_connection.php';

// Check if search term is provided
if (isset($_POST['search'])) {
    // Sanitize the search term to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search']);
    
    // Query to search for products based on product name
    $query = "SELECT * FROM products WHERE product_name LIKE '%$searchTerm%'";
    
    // Execute the query
    $result = mysqli_query($conn, $query);
    
    // Check if any products are found
    if (mysqli_num_rows($result) > 0) {
        // Fetch and display the products
        while ($row = mysqli_fetch_assoc($result)) {
            // Output the product details as needed
            echo '<div class="product-item" data-category="' . $row['pcategory_id'] . '">' . $row['product_name'] . '</div>';
        }
    } else {
        echo 'No products found.';
    }
} else {
    echo 'Search term is required.';
}
?>
