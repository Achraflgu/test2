<?php
// Include your database connection file
include 'db_connection.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize response array
$response = [];

// Get the user IP address
$userIp = $_SERVER['REMOTE_ADDR'];

// Get the current date
$currentDate = date("Y-m-d");

// Fetch cart data from the database based on user IP
$query = "SELECT c.cart_id, c.product_id, c.quantity, c.color, c.size, p.product_name, p.product_price, p.product_sale_price, p.product_tag, p.product_photo, p.sale_start_date, p.sale_end_date 
          FROM cart c
          INNER JOIN products p ON c.product_id = p.product_id
          WHERE c.user_ip = '$userIp'";
$result = mysqli_query($conn, $query);

if ($result) {
    $cartData = [];
    $cartTotal = 0;
    $cartCount = mysqli_num_rows($result);

    while ($row = mysqli_fetch_assoc($result)) {
        // Determine the price based on whether the product is on sale and within the sale period
        $price = $row['product_price'];
        if ($row['product_tag'] === 'Sale' && $currentDate >= $row['sale_start_date'] && $currentDate <= $row['sale_end_date']) {
            $price = $row['product_sale_price'];
        }

        $subtotal = $row['quantity'] * $price;
        $cartTotal += $subtotal;

        $cartData[] = [
            'cart_id' => $row['cart_id'],
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity'],
            'color' => $row['color'],
            'size' => $row['size'],
            'product_name' => $row['product_name'],
            'product_price' => $row['product_price'], // Include regular price
            'product_sale_price' => $row['product_sale_price'], // Include sale price
            'product_tag' => $row['product_tag'],
            'product_photo' => $row['product_photo'],
            'sale_start_date' => $row['sale_start_date'], // Add sale start date
            'sale_end_date' => $row['sale_end_date'], // Add sale end date
            'subtotal' => $subtotal
        ];
        
        
    }

    // Set success response
    $response['status'] = 'success';
    $response['cartTotal'] = $cartTotal;
    $response['cartCount'] = $cartCount;
    $response['products'] = $cartData;

    // Set content type to JSON
    header('Content-Type: application/json');

    // Return JSON response
    echo json_encode($response);
} else {
    // Set error response
    $response['status'] = 'error';
    $response['message'] = 'Failed to fetch cart data';
    
    // Set content type to JSON
    header('Content-Type: application/json');

    // Return JSON response with error message
    echo json_encode($response);
}
?>
