<?php
include 'db_connection.php';
session_start();

if(isset($_SESSION['customer_email'])){
    $customerEmail = $_SESSION['customer_email'];

    // Get customer ID from database using customer email
    $sql = "SELECT customer_id FROM customers WHERE customer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customerEmail]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $customerId = $row['customer_id'];

    // Fetch wishlist items for the customer
    $sql = "SELECT product_id FROM wishlist WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customerId]);
    $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($wishlistItems);
}
?>
