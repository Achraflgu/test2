<?php
session_start();

include("db_connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (isset($_SESSION['customer_email'])) {
        // Retrieve form data
        $user_email = $_SESSION['customer_email'];
        $comment = $_POST['comment'];
        $blog_id = $_POST['blog_id'];

        // Get the current date and time
        $current_date = date('Y-m-d H:i:s');

        // Check if the comment is empty
        if (!empty($comment)) {
            // Check if the user has already left a comment for this blog post
            $check_comment_sql = "SELECT * FROM blogreviews WHERE blog_id = $blog_id AND customer_id IN (SELECT customer_id FROM customers WHERE customer_email = '$user_email')";
            $check_comment_result = mysqli_query($conn, $check_comment_sql);
            $existing_comment = mysqli_fetch_assoc($check_comment_result);

            if ($existing_comment) {
                // If the user has already left a comment, update the existing comment
                $review_id = $existing_comment['review_id'];
                $update_comment_sql = "UPDATE blogreviews SET review_content = '$comment', date_posted = '$current_date' WHERE review_id = $review_id";
                mysqli_query($conn, $update_comment_sql);
            } else {
                // If the user has not left a comment, insert a new comment
                $customer_id_sql = "SELECT customer_id FROM customers WHERE customer_email = '$user_email'";
                $customer_id_result = mysqli_query($conn, $customer_id_sql);
                $customer_id = mysqli_fetch_assoc($customer_id_result)['customer_id'];

                $insert_comment_sql = "INSERT INTO blogreviews (blog_id, customer_id, review_content, date_posted) VALUES ($blog_id, $customer_id, '$comment', '$current_date')";
                mysqli_query($conn, $insert_comment_sql);
            }
        } else {
            // If the comment is empty, delete the existing comment (if any)
            $delete_comment_sql = "DELETE FROM blogreviews WHERE blog_id = $blog_id AND customer_id IN (SELECT customer_id FROM customers WHERE customer_email = '$user_email')";
            mysqli_query($conn, $delete_comment_sql);
        }

        // Redirect back to the blog post after submitting the comment
        header("Location: blog.php?blog_id=$blog_id");
        exit();
    } else {
        // If the user is not logged in, redirect to the login page or display a message
        header("Location: login.php");
        exit();
    }
} else {
    // If the form is not submitted, redirect to the home page or display an error message
    header("Location: index.php");
    exit();
}
?>
