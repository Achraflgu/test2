<?php
ob_start();
session_start();

include("db_connection.php"); // Include your database connection file here

// Check if confirmation code is provided in the URL
if (isset($_GET['code'])) {
    $confirmation_code = $_GET['code'];

    // Retrieve registration details associated with this confirmation code from session
    if (isset($_SESSION['registration_details'])) {
        $registration_details = $_SESSION['registration_details'];

        // Check if the provided confirmation code matches the one saved in the session
        if ($registration_details['confirmation_code'] == $confirmation_code) {
            // Save user details to database
            // Example query to insert into your 'customers' table
            $query = "INSERT INTO customers (customer_name, customer_email, customer_password, customer_address, customer_city, customer_postal_code, customer_country, customer_phone, customers_photo) VALUES ('{$registration_details['customer_name']}', '{$registration_details['customer_email']}', '{$registration_details['customer_password']}', '{$registration_details['customer_address']}', '{$registration_details['customer_city']}', '{$registration_details['customer_postal_code']}', '{$registration_details['customer_country']}', '{$registration_details['customer_phone']}', '{$registration_details['customer_photo']}')";

            // Execute the query
            // Assuming you have a function to execute queries in your database connection file
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Registration successful, display success message
                echo "Registration successful! You can now login.";
                header("Location: login.php");
                exit();
            } else {
                // Registration failed, display error message
                echo "Registration failed. Please try again later.";
            }
        } else {
            // Confirmation code does not match, display error message
            echo "Invalid confirmation code.";
        }
    } else {
        // Session data not found, display error message
        echo "Session data not found. Please try again.";
    }
} else {
    // Confirmation code not provided, display error message
    echo "Confirmation code not provided.";
}
?>
