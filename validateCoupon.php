<?php
// Include your database connection file
include("db_connection.php");

// Start the session
session_start();

// Check if the coupon code is provided in the POST request
if (isset($_POST['coupon_code'])) {
    $couponCode = $_POST['coupon_code'];

    // Prepare the query using a prepared statement to prevent SQL injection
    $query = "SELECT * FROM coupons WHERE coupon_code = ? AND expiry_date >= CURDATE() AND usage_count <= limit_usage";
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind the coupon code parameter to the prepared statement
    mysqli_stmt_bind_param($stmt, "s", $couponCode);
    
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
    
    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Coupon code exists and is valid
            $couponData = mysqli_fetch_assoc($result);
            $discountAmount = $couponData['discount'];

            // Fetch the list of products eligible for discount
            $discountedProducts = array(); // Initialize an empty array

            // Query the database to fetch product IDs associated with the coupon
            $couponId = $couponData['coupon_id'];
            $queryProducts = "SELECT product_id FROM product_coupons WHERE coupon_id = $couponId";
            $resultProducts = mysqli_query($conn, $queryProducts);

            if ($resultProducts) {
                while ($row = mysqli_fetch_assoc($resultProducts)) {
                    // You can fetch additional product details if needed
                    $product = array(
                        'product_id' => $row['product_id'],
                        'discount_percentage' => $couponData['discount'] // Assuming the discount percentage is stored in the coupon table
                    );
                    $discountedProducts[] = $product;
                }
            }

            // Increase the usage count of the coupon
            /*$updateQuery = "UPDATE coupons SET usage_count = usage_count + 1 WHERE coupon_code = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "s", $couponCode);
            mysqli_stmt_execute($stmt);*/

            // Store coupon code in session
            $_SESSION['couponCode'] = $couponCode;

            // Return the discount amount and the list of discounted products as JSON
            $response = array(
                'status' => 'success',
                'discount_amount' => $discountAmount,
                'discounted_products' => $discountedProducts
            );
            echo json_encode($response);
        } else {
            // Coupon code is invalid or expired
            $response = array('status' => 'error', 'message' => 'Invalid coupon code');
            echo json_encode($response);
        }
    } else {
        // Error in executing the query
        $response = array('status' => 'error', 'message' => 'Error in database query');
        echo json_encode($response);
    }

} else {
    // Coupon code is not provided in the POST request
    $response = array('status' => 'error', 'message' => 'Coupon code is missing');
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
?>
