<?php
ob_start();
session_start();

include("db_connection.php");
include("header.php");
include("nav.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_email = $_POST['customer_email'];
    $customer_password = $_POST['customer_password'];

    $sql = "SELECT * FROM customers WHERE customer_email = '$customer_email' AND customer_password = '$customer_password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['customer_email'] = $customer_email;
        $_SESSION['customer_name'] = $row['customer_name'];
        echo "Login successful!"; // Debug message
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid email or password!";
        echo "Invalid email or password!"; // Debug message
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
        <div class="register-title text-center"> <!-- Adding text-center class to center align -->
            <h4>Login your account</h4>
            <p>Login to your account to discover all great features in this item</p>
        </div>

        <div class="row justify-content-center"> <!-- Center aligning the row -->
            <div class="col-sm-12 col-md-12 col-lg-6">
                <!-- .register-title end -->
                <form method="post" action="">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="customer_email" id="user-name" placeholder="Email" required>
                            </div>
                        </div>
                        <!-- .col-lg-12 end -->
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="customer_password" id="login-password" placeholder="Your Password" required>
                            </div>
                        </div>
                        <!-- .col-lg-12 end -->
                        <div class="col-sm-12 col-md-12 col-lg-12 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="input-checkbox inline-block">
                                    <label class="label-checkbox">Keep me logged in
                                        <input type="checkbox" checked>
                                        <span class="check-indicator"></span>
                                    </label>
                                </div>
                                <a href="#" class="forget--password">Forgot your password?</a>
                            </div>
                        </div>
                        <!-- .col-lg-12 end -->
                        <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                            <div class="mb-3">
                            <button type="submit" class="btn btn--primary btn--rounded">Login</button>
                            </div>
                            <!-- .mb-3 end -->
                            <div class="already-customer">
                                <p class="mb-0">Don't have an account? <a href="register.php">Register here</a></p>
                            </div>
                        </div>
                        <!-- .col-lg-12 end -->
                        <?php if (isset($error_message)) : ?>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- .row end -->
                </form>
            </div>
            <!-- .col-lg-6 end -->

            <!-- .col-lg-6 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>

<!-- #checkout end -->
<?php
include("footer.php");
ob_end_flush();
?>