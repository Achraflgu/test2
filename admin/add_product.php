<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include 'db_connection.php';

    // Handle form data
    $productName = $_POST['productName'];
    $productCategory = $_POST['productCategory'];
    $productDescription = $_POST['productDescription'];
    $productPrice = $_POST['productPrice'];
    $productStock = $_POST['productStock'];
    $brandId = $_POST['brandId'];
    $productFeatures = $_POST['productFeatures'];
    $productDetails = $_POST['productDetails'];
    $productTag = $_POST['productTag'];
    $productUrl = $_POST['productUrl']; // New field: Product URL
    
    // Decode the product keywords JSON string into PHP array
    $productKeywords = json_decode($_POST['productKeywords'], true);

    // Check if the product tag is "Sale"
    if ($productTag == "Sale") {
        // Handle sale-related fields if the product is on sale
        $productSalePrice = isset($_POST['productSalePrice']) ? $_POST['productSalePrice'] : null;
        $productSaleStartDate = isset($_POST['productSaleStartDate']) ? $_POST['productSaleStartDate'] : null;
        $productSaleEndDate = isset($_POST['productSaleEndDate']) ? $_POST['productSaleEndDate'] : null;
    } else {
        // Set default values or null for sale-related fields if the product is not on sale
        $productSalePrice = null;
        $productSaleStartDate = null;
        $productSaleEndDate = null;
    }

    // Handle file upload (main product photo)
    $targetDir = "uploads/";
    $uploadOk = 1;

    // Function to handle file upload for each product photo
    function handleFileUpload($fileKey, $targetDir) {
        global $uploadOk;
        $targetFile = $targetDir . basename($_FILES[$fileKey]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file field is empty
        if (empty($_FILES[$fileKey]["name"])) {
            return null; // Return null if file field is empty
        }

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES[$fileKey]["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
            return null;
        }

        // Check file size
        if ($_FILES[$fileKey]["size"] > 500000) {
            $uploadOk = 0;
            return null;
        }

        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            return null;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // If there's an error with the file upload
            return null;
        } else {
            if (move_uploaded_file($_FILES[$fileKey]["tmp_name"], $targetFile)) {
                // File uploaded successfully
                return $targetFile;
            } else {
                // File upload failed
                return null;
            }
        }
    }

    // Handle main product photo upload
    $productPhoto = handleFileUpload("productPhoto", $targetDir);

    // Handle additional product photos uploads
    $productPhoto1 = handleFileUpload("productPhoto1", $targetDir);
    $productPhoto2 = handleFileUpload("productPhoto2", $targetDir);
    $productPhoto3 = handleFileUpload("productPhoto3", $targetDir);

    // Proceed with database insertion if main product photo is uploaded successfully
    if ($productPhoto !== null) {
        // Prepare SQL statement
        $sql = "INSERT INTO products (product_name, pcategory_id, product_description, product_price, product_stock_quantity, brand_id, product_photo, product_features, product_details, product_tag, product_sale_price, sale_start_date, sale_end_date, product_photo_1, product_photo_2, product_photo_3, product_url, product_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sisdiissssssssssss", $productName, $productCategory, $productDescription, $productPrice, $productStock, $brandId, $productPhoto, $productFeatures, $productDetails, $productTag, $productSalePrice, $productSaleStartDate, $productSaleEndDate, $productPhoto1, $productPhoto2, $productPhoto3, $productUrl, $_POST['productKeywords']);

        // Execute statement
        $stmt->execute();

        // Check if affected rows are greater than 0
        if ($stmt->affected_rows > 0) {
            // Product added successfully
            $response = array('success' => true, 'message' => 'Product added successfully');
        } else {
            // Product addition failed
            $response = array('success' => false, 'message' => 'Failed to add product');
        }

        // Close statement
        $stmt->close();
    } else {
        // Proceed with database insertion even if no additional product photos are provided
        // Prepare SQL statement
        $sql = "INSERT INTO products (product_name, pcategory_id, product_description, product_price, product_stock_quantity, brand_id, product_photo, product_features, product_details, product_tag, product_sale_price, sale_start_date, sale_end_date, product_url, product_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sisdiisssssssssss", $productName, $productCategory, $productDescription, $productPrice, $productStock, $brandId, $productPhoto, $productFeatures, $productDetails, $productTag, $productSalePrice, $productSaleStartDate, $productSaleEndDate, $productUrl, $_POST['productKeywords']);

        // Execute statement
        $stmt->execute();

        // Check if affected rows are greater than 0
        if ($stmt->affected_rows > 0) {
            // Product added successfully
            $response = array('success' => true, 'message' => 'Product added successfully');
        } else {
            // Product addition failed
            $response = array('success' => false, 'message' => 'Failed to add product');
        }

        // Close statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If form is not submitted
    $response = array('success' => false, 'message' => 'Form not submitted');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
