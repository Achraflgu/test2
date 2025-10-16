<?php
// Include database connection
include_once 'db_connection.php';

// Check if coupon ID is set in the POST data
if(isset($_POST['coupon_id'])) {
    $couponId = $_POST['coupon_id'];

    // Fetch coupon details from the database
    $sql = "SELECT * FROM coupons WHERE coupon_id = $couponId";
    $result = mysqli_query($conn, $sql);

    if($result) {
        // Fetch the data as an associative array
        $couponData = mysqli_fetch_assoc($result);
        
        // Fetch associated product IDs
        $productIds = array();
        $productSql = "SELECT product_id FROM product_coupons WHERE coupon_id = $couponId";
        $productResult = mysqli_query($conn, $productSql);
        while($row = mysqli_fetch_assoc($productResult)) {
            $productIds[] = $row['product_id'];
        }
        $couponData['product_ids'] = $productIds;
        
        // Send the coupon data as JSON response
        echo json_encode($couponData);
    } else {
        // Handle query error
        $error = mysqli_error($conn);
        echo json_encode(array('error' => $error));
    }
} else {
    // Handle case when coupon ID is not set
    echo json_encode(array('error' => 'Coupon ID is not set'));
}
?>
