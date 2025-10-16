<?php
// Include your database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_email'])) {
    // If the user is not logged in, prompt them to log in
    echo "You must be logged in to add a review.";
    exit;
}

// Check if the form data is received
if (!isset($_POST['review_text']) || !isset($_POST['rating']) || !isset($_POST['product_id'])) {
    // If one or more required fields are missing, inform the user
    echo "One or more required fields are missing.";
    echo "<br>";
    echo "Received data:";
    var_dump($_POST);
    exit;
}

// Retrieve form data
$reviewText = $_POST['review_text'];
$rating = $_POST['rating'];
$productId = $_POST['product_id']; // Assuming product ID is received from the form

// Sanitize the input
$reviewText = htmlspecialchars($reviewText);

// Validate form data
if (empty($reviewText)) {
    // If review text is empty, inform the user
    echo "Review text is required.";
    exit;
}

// Retrieve the customer ID from the session
$customerEmail = $_SESSION['customer_email'];
$query = "SELECT customer_id FROM customers WHERE customer_email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $customerEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    // If there's an error retrieving customer data, inform the user
    echo "Error retrieving customer data: " . mysqli_error($conn);
    exit;
}

$row = mysqli_fetch_assoc($result);
$customerId = $row['customer_id'];

// Insert the review into the database
// Insert the review into the database
$query = "INSERT INTO productreviews (product_id, customer_id, review_text, rating, review_date) VALUES (?, ?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iisi", $productId, $customerId, $reviewText, $rating);
mysqli_stmt_execute($stmt);


// Check if the review was inserted successfully
if (mysqli_stmt_affected_rows($stmt) > 0) {
    // If the review was added successfully, inform the user
    echo "Review added successfully.";
    header("Location: product.php?id=" . $productId);
} else {
    // If there's an error adding the review, inform the user
    echo "Error adding review: " . mysqli_error($conn);
}

// Close the statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
