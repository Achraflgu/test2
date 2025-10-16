<?php
// Include your database connection file here
include 'db_connection.php';

// Start the session
session_start();

// Initialize response array
$response = [];

// Check if action is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Get user IP address
    $userIp = $_SERVER['REMOTE_ADDR'];

    if ($action === 'add' && isset($_POST['productId'])) {
        $productId = $_POST['productId'];
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Set default quantity to 1 if not provided

        // Check if product is already in cart with the same size
        if (isset($_POST['size'])) {
            $size = $_POST['size'];
            $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND size = ? AND user_ip = ?");
            $stmt->bind_param("iss", $productId, $size, $userIp);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Update quantity if the same product with the same size is already in cart
                $existingProduct = $result->fetch_assoc();
                $newQuantity = $existingProduct['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND size = ? AND user_ip = ?");
                $stmt->bind_param("iiss", $newQuantity, $productId, $size, $userIp);
                $stmt->execute();
            } else {
                // Add new product to cart with the specified size
                $stmt = $conn->prepare("INSERT INTO cart (product_id, quantity, user_ip, size) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $productId, $quantity, $userIp, $size);
                $stmt->execute();
            }
        } else {
            // If size is not provided, handle adding the product without considering size
            // This part remains the same as before
            // Check if product is already in cart
            $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND user_ip = ?");
            $stmt->bind_param("is", $productId, $userIp);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Update quantity if product is already in cart
                $existingProduct = $result->fetch_assoc();
                $newQuantity = $existingProduct['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND user_ip = ?");
                $stmt->bind_param("iis", $newQuantity, $productId, $userIp);
                $stmt->execute();
            } else {
                // Add new product to cart
                $stmt = $conn->prepare("INSERT INTO cart (product_id, quantity, user_ip) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $productId, $quantity, $userIp);
                $stmt->execute();
            }
        }

        // Store color information if provided
        if (isset($_POST['color'])) {
            $color = $_POST['color'];
            // Update the color field in the cart table for the corresponding product ID
            $stmt = $conn->prepare("UPDATE cart SET color = ? WHERE product_id = ? AND user_ip = ?");
            $stmt->bind_param("sis", $color, $productId, $userIp);
            $stmt->execute();
        }
    } elseif ($action === 'remove' && isset($_POST['cartId'])) {
        // If action is 'remove' and cartId is set, remove the product from the cart
        $cartId = $_POST['cartId'];

        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_ip = ?");
        $stmt->bind_param("is", $cartId, $userIp);
        $stmt->execute();
    } elseif (($action === 'increase' || $action === 'decrease') && isset($_POST['cartId'])) {
        // If action is 'increase' or 'decrease' and cartId is set, update the quantity of the product in the cart
        $cartId = $_POST['cartId'];

        if ($action === 'increase') {
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE cart_id = ? AND user_ip = ?");
        } elseif ($action === 'decrease') {
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE cart_id = ? AND user_ip = ?");
        }

        $stmt->bind_param("is", $cartId, $userIp);
        $stmt->execute();
    } else {
        $response = ['status' => 'error', 'message' => 'Invalid request'];
        echo json_encode($response);
        exit();
    }

    // Get updated cart data
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_ip = ?");
    $stmt->bind_param("s", $userIp);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    // Calculate cart total
    $stmt = $conn->prepare("SELECT SUM(product_price * quantity) as cartTotal FROM cart JOIN products ON cart.product_id = products.product_id WHERE user_ip = ?");
    $stmt->bind_param("s", $userIp);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cartTotal = $row['cartTotal'];

    // Get cart count
    $cartCount = count($cartItems);

    $response = [
        'status' => 'success',
        'cartTotal' => $cartTotal,
        'cartCount' => $cartCount,
        'cartItems' => $cartItems
    ];
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request'];
}

// Return response as JSON
echo json_encode($response);

// Close database connection
$stmt->close();
$conn->close();
?>
