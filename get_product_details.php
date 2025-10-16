<?php
include("db_connection.php");

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    // Fetch product details
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Fetch average rating
    $query = "SELECT AVG(rating) as avg_rating FROM productreviews WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $avg_rating = $result->fetch_assoc()['avg_rating'];

    // Count the number of reviews
    $query_reviews_count = "SELECT * FROM productreviews WHERE product_id = ?";
    $stmt_reviews_count = $conn->prepare($query_reviews_count);
    $stmt_reviews_count->bind_param("i", $product_id);
    $stmt_reviews_count->execute();

    $result_reviews_count = $stmt_reviews_count->get_result();
    $num_reviews = $result_reviews_count->num_rows;

    // Add average rating and number of reviews to the product array
    $product['avg_rating'] = round($avg_rating, 1);
    $product['reviews'] = $num_reviews;

    // Add current date to the product array
    $product['currentDate'] = date("Y-m-d");

    // Return the product details as JSON
    echo json_encode($product);
}
?>
