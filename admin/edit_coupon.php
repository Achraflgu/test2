<?php
// Include database connection
include_once 'db_connection.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $editCouponId = $_POST['editCouponId'];
    $editCouponName = mysqli_real_escape_string($conn, $_POST['editCouponName']);
    $editCouponCode = mysqli_real_escape_string($conn, $_POST['editCouponCode']);
    $editDiscount = $_POST['editDiscount'];
    $editExpiryDate = $_POST['editExpiryDate'];
    $editLimitUsage = $_POST['editLimitUsage'];
    $editProducts = $_POST['editProducts']; // Array of selected product IDs
    
    // Check if the coupon ID exists in the coupons table
    $checkCouponQuery = "SELECT * FROM coupons WHERE coupon_id = '$editCouponId'";
    $checkCouponResult = mysqli_query($conn, $checkCouponQuery);
    
    if (mysqli_num_rows($checkCouponResult) > 0) {
        // Update the coupon details in the database
        $sql = "UPDATE coupons SET 
                coupon_name = '$editCouponName', 
                coupon_code = '$editCouponCode', 
                discount = '$editDiscount', 
                expiry_date = '$editExpiryDate', 
                limit_usage = '$editLimitUsage' 
                WHERE coupon_id = '$editCouponId'";

        if (mysqli_query($conn, $sql)) {
            // Delete existing product associations for this coupon
            $deleteQuery = "DELETE FROM product_coupons WHERE coupon_id = '$editCouponId'";
            mysqli_query($conn, $deleteQuery);

            // Insert new product associations for this coupon
            foreach ($editProducts as $productId) {
                $insertQuery = "INSERT INTO product_coupons (coupon_id, product_id) VALUES ('$editCouponId', '$productId')";
                mysqli_query($conn, $insertQuery);
            }

            // Handle success
            $response = array("success" => true, "message" => "Coupon updated successfully");
            echo json_encode($response);
        } else {
            // Handle database error
            $error = mysqli_error($conn);
            $response = array("success" => false, "error" => $error);
            echo json_encode($response);
        }
    } else {
        // Handle case when coupon ID does not exist
        $response = array("success" => false, "error" => "Coupon ID does not exist");
        echo json_encode($response);
    }
} else {
    // Handle case when form data is not submitted
    $response = array("success" => false, "error" => "Form data not submitted");
    echo json_encode($response);
}
?>
