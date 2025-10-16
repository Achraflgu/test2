<?php
// Include database connection
include_once 'db_connection.php'; // Assuming you have this file

// Check if productId is provided in the POST request
if (isset($_POST['productId'])) {
    // Sanitize the input to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_POST['productId']);

    // Fetch product details from the database
    $query = "SELECT p.*, pc.pcategory_id, pc.pcategory_name, b.brand_id, b.brand_name
              FROM products p
              LEFT JOIN productcategories pc ON p.pcategory_id = pc.pcategory_id
              LEFT JOIN brands b ON p.brand_id = b.brand_id
              WHERE p.product_id = '$productId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if the product exists
        if (mysqli_num_rows($result) > 0) {
            $productDetails = mysqli_fetch_assoc($result);

            // Fetch product keywords from the database
            $productKeywordsQuery = "SELECT product_keywords FROM products WHERE product_id = '$productId'";
            $productKeywordsResult = mysqli_query($conn, $productKeywordsQuery);

            if ($productKeywordsResult) {
                $productKeywordsRow = mysqli_fetch_assoc($productKeywordsResult);
                $productDetails['product_keywords'] = $productKeywordsRow['product_keywords'];
            } else {
                // Error fetching product keywords
                echo json_encode(array('error' => 'Failed to fetch product keywords'));
                exit; // Stop script execution
            }

            // Fetch all categories and brands from the database
            $queryCategories = "SELECT * FROM productcategories";
            $queryBrands = "SELECT * FROM brands";
            $resultCategories = mysqli_query($conn, $queryCategories);
            $resultBrands = mysqli_query($conn, $queryBrands);

            $allCategories = [];
            $allBrands = [];

            // Store all categories and brands in arrays
            while ($rowCategories = mysqli_fetch_assoc($resultCategories)) {
                $allCategories[] = $rowCategories;
            }

            while ($rowBrands = mysqli_fetch_assoc($resultBrands)) {
                $allBrands[] = $rowBrands;
            }

            // Add all categories and brands to the product details response
            $productDetails['allCategories'] = $allCategories;
            $productDetails['allBrands'] = $allBrands;

            // Return product details as JSON response
            echo json_encode($productDetails);
        } else {
            // Product not found
            echo json_encode(array('error' => 'Product not found'));
        }
    } else {
        // Error fetching product details
        echo json_encode(array('error' => 'Failed to fetch product details'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // productId is not provided
    echo json_encode(array('error' => 'ProductId not provided'));
}
?>
