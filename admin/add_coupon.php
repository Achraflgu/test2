<?php
// Include database connection
include_once 'db_connection.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $addCouponName = mysqli_real_escape_string($conn, $_POST['addCouponName']);
    $addCouponCode = mysqli_real_escape_string($conn, $_POST['addCouponCode']);
    $addDiscount = $_POST['addDiscount'];
    $addExpiryDate = $_POST['addExpiryDate'];
    $addLimitUsage = $_POST['addLimitUsage'];
    $addProducts = $_POST['addProducts']; // Array of selected product IDs
    
    // Insert the new coupon details into the database
    $sql = "INSERT INTO coupons (coupon_name, coupon_code, discount, expiry_date, limit_usage) 
            VALUES ('$addCouponName', '$addCouponCode', '$addDiscount', '$addExpiryDate', '$addLimitUsage')";

    if (mysqli_query($conn, $sql)) {
        // Get the ID of the newly inserted coupon
        $newCouponId = mysqli_insert_id($conn);
        
        // Insert product associations for this coupon
        foreach ($addProducts as $productId) {
            $insertQuery = "INSERT INTO product_coupons (coupon_id, product_id) 
                            VALUES ('$newCouponId', '$productId')";
            mysqli_query($conn, $insertQuery);
        }

        // Handle success
        $response = array("success" => true, "message" => "Coupon added successfully");
        echo json_encode($response);
    } else {
        // Handle database error
        $error = mysqli_error($conn);
        $response = array("success" => false, "error" => $error);
        echo json_encode($response);
    }
} else {
    // Handle case when form data is not submitted
    $response = array("success" => false, "error" => "Form data not submitted");
    echo json_encode($response);
}
?>
