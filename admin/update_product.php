<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Check if all required parameters are provided in the POST request
if (
    isset($_POST['productId']) &&
    isset($_POST['productName']) &&
    isset($_POST['productStock']) &&
    isset($_POST['productPrice']) &&
    isset($_POST['productUrl']) &&
    isset($_POST['productCategory']) &&
    isset($_POST['productBrand']) &&
    isset($_POST['productTag']) &&
    isset($_POST['saleStartDate']) &&
    isset($_POST['saleEndDate']) &&
    isset($_POST['productSalePrice']) &&
    isset($_POST['productDesc']) &&
    isset($_POST['productFeatures']) &&
    isset($_POST['productDetails']) &&
    isset($_POST['productKeywords'])
) {
    // Sanitize the input to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_POST['productId']);
    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productStock = mysqli_real_escape_string($conn, $_POST['productStock']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['productPrice']);
    $productUrl = mysqli_real_escape_string($conn, $_POST['productUrl']);
    $productCategory = mysqli_real_escape_string($conn, $_POST['productCategory']);
    $productBrand = mysqli_real_escape_string($conn, $_POST['productBrand']);
    $productTag = mysqli_real_escape_string($conn, $_POST['productTag']);
    $saleStartDate = mysqli_real_escape_string($conn, $_POST['saleStartDate']);
    $saleEndDate = mysqli_real_escape_string($conn, $_POST['saleEndDate']);
    $salePrice = mysqli_real_escape_string($conn, $_POST['productSalePrice']);
    $productDesc = mysqli_real_escape_string($conn, $_POST['productDesc']);
    $productFeatures = mysqli_real_escape_string($conn, $_POST['productFeatures']);
    $productDetails = mysqli_real_escape_string($conn, $_POST['productDetails']);
    $productKeywords = mysqli_real_escape_string($conn, $_POST['productKeywords']);

    // Update product details in the database
    $query = "UPDATE products SET 
                product_name='$productName', 
                product_stock_quantity='$productStock', 
                product_price='$productPrice', 
                product_url='$productUrl', 
                pcategory_id='$productCategory', 
                brand_id='$productBrand',
                product_tag='$productTag',
                sale_start_date='$saleStartDate',
                sale_end_date='$saleEndDate',
                product_sale_price='$salePrice',
                product_description='$productDesc',
                product_features='$productFeatures',
                product_details='$productDetails',
                product_keywords='$productKeywords'
              WHERE product_id='$productId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if the main product image is updated
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == UPLOAD_ERR_OK) {
            // Handle main product image upload
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                // Update the product photo in the database
                $query = "UPDATE products SET product_photo='$targetFile' WHERE product_id='$productId'";
                $result = mysqli_query($conn, $query);
            }
        }

        // Check if other product images are updated
        for ($i = 1; $i <= 3; $i++) {
            $inputName = "otherImage" . $i;
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
                // Handle other product image upload
                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES[$inputName]["name"]);
                if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
                    // Update the corresponding product photo in the database
                    $column = "product_photo_" . $i;
                    $query = "UPDATE products SET $column='$targetFile' WHERE product_id='$productId'";
                    $result = mysqli_query($conn, $query);
                }
            }
        }

        // Check if all photo updates are successful
        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Failed to update product photos'));
        }
    } else {
        // Error updating product details
        echo json_encode(array('error' => 'Failed to update product details'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Required parameters not provided
    echo json_encode(array('error' => 'Required data is missing.'));
}
?>
