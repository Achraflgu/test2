<?php
include 'db_connection.php';
session_start();

if(!isset($_SESSION['customer_email'])){
    echo 'not_logged_in';
    exit;
}

if(isset($_POST['action']) && isset($_POST['product_id'])){
    $customerEmail = $_SESSION['customer_email'];

    // Get customer ID from database using customer email
    $sql = "SELECT customer_id FROM customers WHERE customer_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customerEmail]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $customerId = $row['customer_id'];

    $productId = $_POST['product_id'];

    if($_POST['action'] === 'add'){
        // Add product to wishlist
        $sql = "INSERT INTO wishlist (customer_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId, $productId]);
    } elseif($_POST['action'] === 'remove'){
        // Remove product from wishlist
        $sql = "DELETE FROM wishlist WHERE customer_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customerId, $productId]);
    }

    echo 'success';
}
?>
