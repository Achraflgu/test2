<?php
include 'db_connection.php';
session_start();

if(isset($_SESSION['customer_email'])){
    $customerEmail = $_SESSION['customer_email'];

    // Get customer ID from database using customer email
    $sql = "SELECT customer_id FROM customers WHERE customer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $customerEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $customerId = $row['customer_id'];

    // Fetch wishlist items for the customer
    $sql = "SELECT product_id FROM wishlist WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $wishlistItems = [];

    while($row = $result->fetch_assoc()){
        $wishlistItems[] = $row;
    }

    echo json_encode($wishlistItems);
}
?>
