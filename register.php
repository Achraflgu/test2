<?php
ob_start();
session_start();

include("db_connection.php");
include("header.php");
include("nav.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'admin/vendor/autoload.php'; // Path to PHPMailer autoload.php

// Define variables
$success_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_password = $_POST['customer_password'];
    $customer_address = $_POST['customer_address'];
    $customer_city = $_POST['customer_city'];
    $customer_postal_code = $_POST['customer_postal_code'];
    $customer_country = $_POST['customer_country'];
    $customer_phone = $_POST['customer_phone'];

    // File upload
    $target_dir = "admin/uploads/";
    $target_file = $target_dir . basename($_FILES["customers_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["customers_photo"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $error_message = "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $error_message = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["customers_photo"]["size"] > 500000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
     else {
        if (move_uploaded_file($_FILES["customers_photo"]["tmp_name"], $target_file)) {
            $success_message = "The file ". htmlspecialchars( basename( $_FILES["customers_photo"]["name"])). " has been uploaded.";
            
            // Generate confirmation code
            $confirmation_code = bin2hex(random_bytes(16));

            // Save registration details and confirmation code in session
            $_SESSION['registration_details'] = array(
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_password' => $customer_password,
                'customer_address' => $customer_address,
                'customer_city' => $customer_city,
                'customer_postal_code' => $customer_postal_code,
                'customer_country' => $customer_country,
                'customer_phone' => $customer_phone,
                'confirmation_code' => $confirmation_code,
                'customer_photo' => $target_file
            );

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                // SMTP configuration (same as above)
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'achrafgu92@gmail.com';
                $mail->Password = 'frfpvyagagwfwhju';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;                               // TCP port to connect to

                //Recipients
                $mail->setFrom('achrafgu92@gmail.com', 'SHADOW FIT');
                $mail->addAddress($customer_email, $customer_name);     // Add a recipient
                
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Confirm Your Registration';
                ob_start();

                // Include the PHP file which contains the HTML content
                include('ConfirmationMailer.php');

                $htmlContent = ob_get_clean();


                $mail->Body    = $htmlContent;
                
                $mail->send();
                
                $success_message .= " A confirmation email has been sent to your email address. Please click on the link provided to complete your registration.";
            } catch (Exception $e) {
                $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!-- Page Title #1
============================================= -->
<section id="page-title" class="page-title mt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-1 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Login & Register</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb">
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active">Login & Register</li>
                    </ol>
                </div>
                <!-- .title end -->
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #page-title end -->

<!-- register-login
============================================= -->
<section id="register-login" class="register-login pt-30 pb-150">
    <div class="container">
        <div class="register-title text-center mb-4">
            <h4>Register account now</h4>
            <p>Pellentesque habitant morbi tristique senectus et netus et</p>
        </div>
        <!-- .register-title end -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Your Name">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="email" class="form-control" name="customer_email" id="customer_email" placeholder="Email">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="password" class="form-control" name="customer_password" id="customer_password" placeholder="Password">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_address" id="customer_address" placeholder="Address">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_city" id="customer_city" placeholder="City">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_postal_code" id="customer_postal_code" placeholder="Postal Code">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_country" id="customer_country" placeholder="Country">
                </div>
                <!-- .col-md-6 end -->
                <div class="col-sm-12 col-md-6 mb-3">
                    <input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="Phone">
                </div>
                <!-- .col-md-6 end -->
            </div>
            <!-- .row end -->

            <!-- File Upload Field -->
            <div class="col-sm-12 col-md-12 mb-4">
                <div class="form-group">
                    <div class="form-control" style="position: relative; overflow: hidden;">
                        <input type="file" id="customers_photo" name="customers_photo" style="position: absolute; font-size: 100px; opacity: 0;">
                        <label for="customers_photo" style="cursor: pointer;">Upload Your Profile Photo</label>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <?php if (isset($error_message)) : ?>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (!empty($success_message)) : ?>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="col-sm-12 col-md-12 text-center">
                <div class="mb-3">
                    <button type="submit" class="btn btn--primary btn--rounded">Register</button>
                </div>
                <!-- .mb-3 end -->
                <div class="already-customer">
                    <p>Already a customer? <a href="login.php">Login here</a></p>
                </div>
            </div>
            <!-- .col-md-12 end -->
        </form>
    </div>
    <!-- .container end -->
</section>

<!-- #checkout end -->
<?php
include("footer.php");
ob_end_flush();
?>
