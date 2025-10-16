<?php
// Include database connection
include_once 'db_connection.php';

// Check if coupon ID is provided
if(isset($_POST['coupon_id'])) {
    // Sanitize the coupon ID
    $coupon_id = mysqli_real_escape_string($conn, $_POST['coupon_id']);

    // Delete associated product-coupon mappings
    $delete_product_mapping_query = "DELETE FROM product_coupons WHERE coupon_id = '$coupon_id'";
    if(mysqli_query($conn, $delete_product_mapping_query)) {
        // Now that associated mappings are deleted, proceed to delete the coupon
        $delete_query = "DELETE FROM coupons WHERE coupon_id = '$coupon_id'";
        if(mysqli_query($conn, $delete_query)) {
            // Coupon deleted successfully
            $response = array("success" => true, "message" => "Coupon deleted successfully");
            echo json_encode($response);
        } else {
            // Error deleting coupon
            $error = mysqli_error($conn);
            $response = array("success" => false, "error" => $error);
            echo json_encode($response);
        }
    } else {
        // Error deleting associated mappings
        $error = mysqli_error($conn);
        $response = array("success" => false, "error" => $error);
        echo json_encode($response);
    }
} else {
    // No coupon ID provided
    $response = array("success" => false, "error" => "Coupon ID not provided");
    echo json_encode($response);
}
?>
