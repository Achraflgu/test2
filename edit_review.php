<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include your database connection file
include 'db_connection.php';

// Check if the user is logged in
if (isset($_SESSION['customer_email'])) {
    // Check if the form data is received
    if (isset($_POST['review_id'], $_POST['review_text'], $_POST['rating'])) {
        // Retrieve form data
        $reviewId = $_POST['review_id'];
        $reviewText = $_POST['review_text'];
        $rating = $_POST['rating'];
        $productId = $_POST['product_id'];
        // Sanitize the input
        $reviewText = htmlspecialchars($reviewText);

        // Validate form data
        if (empty($reviewText)) {
            header("Location: product.php?id=" . $productId);
            exit;
        }

        // Prepare and execute update query
        // Update the review in the database
        $query = "UPDATE productreviews SET review_text = ?, rating = ?, review_date = NOW() WHERE review_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sii", $reviewText, $rating, $reviewId);
        mysqli_stmt_execute($stmt);


        // Check if the review was updated successfully
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Review updated successfully.";
            // Redirect to the previous page
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                // If the HTTP_REFERER is not set, redirect to a default page
                header("Location: index.php");
            }
            exit; // Make sure to exit after redirection
        } else {
            echo "Error updating review: " . mysqli_error($conn);
        }



        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "One or more required fields are missing.";
    }
} else {
    echo "You must be logged in to edit a review.";
}

// Close the database connection
mysqli_close($conn);
