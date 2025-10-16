<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer autoload file
require 'vendor/autoload.php';

// Include database connection file
include_once 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect if not logged in
    header('Location: login.php');
    exit;
}

// Fetch admin details from the database
$sql = "SELECT admin_name, admin_job FROM admins WHERE admin_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$stmt->bind_result($adminName, $adminJob); // Added admin_job to bind_result
$stmt->fetch();
$stmt->close();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $subject = $_POST['emailSubject'];
    $body = $_POST['emailBody'];
    $customerId = $_POST['customerId'];
    $orderId = $_POST['orderId'];

    // Fetch customer details from the database using $customerId
    $sql = "SELECT customer_name, customer_email FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->bind_result($customerName, $customerEmail);
    $stmt->fetch();
    $stmt->close();

    // Fetch invoice number of the order
    $stmt = $conn->prepare("SELECT invoice_no FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->bind_result($invoiceNo);
    $stmt->fetch();
    $stmt->close();

    // Construct the email body
    $emailBody = "Dear {$customerName},<br><br>";
    $emailBody .= "We hope this email finds you well.<br><br>";
    $emailBody .= "We would like to inform you about an update regarding your recent order. ";
    $emailBody .= "Please find the details below:<br><br>";
    $emailBody .= "{$body}<br><br>";
    $emailBody .= "You can view your order details by clicking on the link below:<br>";
    $emailBody .= "View Order Details: <a href='http://localhost/msport/admin/view_order_details.php?order_id={$orderId}'>{$invoiceNo}</a><br><br>";
    $emailBody .= "If you have any questions or concerns, feel free to reach out to us. ";
    $emailBody .= "We're always here to help.<br><br>";
    $emailBody .= "Best regards,<br>";
    $emailBody .= "{$adminName}, {$adminJob}.<br>";
    $emailBody .= "Customer Service Team<br>";
    $emailBody .= "Your Company Name";
    


    
    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    // Set debug mode
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    // Set mailer to use SMTP
    $mail->isSMTP();

    // SMTP configuration
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'achrafgu92@gmail.com'; // SMTP username
    $mail->Password = 'frfpvyagagwfwhju'; // SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set email parameters
    $mail->setFrom($_SESSION['email'], $adminName); // From email and name
    $mail->addAddress($customerEmail, $customerName); // Recipient email and name
    $mail->Subject = $subject;
    $mail->msgHTML($emailBody);

    // Send the email
    if ($mail->send()) {
        echo 'Message has been sent';
    } else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
} else {
    // Redirect if the form is not submitted
    header('Location: index.php');
    exit;
}
?>
