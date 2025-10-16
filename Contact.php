<?php

session_start();

include("db_connection.php");
include("header.php");
include("nav.php");


?>
<section id="page-title" class="page-title mt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-1 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Contact Us</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb breadcrumb-bottom">
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active">Contact Us</li>
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

<!-- contact #1
=========================================-->
<section id="contact1" class="contact contact-1 pt-50 pb-110">
    <div class="container">
        <div class="row">
            <!-- contact panel #1 -->
            <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="contact-panel">
                    <div class="contact--icon">c</div>
                    <div class="contact--content">
                        <h3>CALL US</h3>
                        <ul class="list-unstyled mb-0">
                            <li><a href="tel:+03234567890">Phone 01: (+032) 3456 7890</a></li>
                            <li><a href="tel:+03234567670">Phone 02: (+032) 3427 7670</a></li>
                        </ul>
                    </div>
                    <!-- .contact-content end -->
                </div>
            </div>
            <!-- .contact-panel end -->
            <!-- contact panel #2 -->
            <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="contact-panel">
                    <div class="contact--icon">v</div>
                    <div class="contact--content">
                        <h3>VISIT US</h3>
                        <ul class="list-unstyled mb-0">
                            <li>Hebes Store London Oxford Street,</li>
                            <li>012 United Kingdom</li>
                        </ul>
                    </div>
                    <!-- .contact-content end -->
                </div>
            </div>
            <!-- .contact-panel end -->
            <!-- contact panel #3 -->
            <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="contact-panel">
                    <div class="contact--icon">E</div>
                    <div class="contact--content">
                        <h3>EMAIL</h3>
                        <ul class="list-unstyled mb-0">
                            <li><a href="mailto:Support@zytheme.net">Support@zytheme.net</a></li>
                            <li><a href="mailto:info@zytheme.net">info@zytheme.net</a></li>
                        </ul>
                    </div>
                    <!-- .contact-content end -->
                </div>
            </div>
            <!-- .contact-panel end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #contact #1 end -->

<!-- google-map
============================================= -->
<section id="google-map" class="google-map pb-0 pt-0">
    <div class="container-fluid pr-0 pl-0">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 pr-0 pl-0">
                <script src="https://www.google.com/maps/place/Tunisie/data=!4m2!3m1!1s0x125595448316a4e1:0x3a84333aaa019bef?sa=X&ved=1t:242&ictx=111"></script>
                <div id="googleMap" class="googleMap" data-map-address="121 King St,Melbourne, Australia" data-map-zoom="12" data-map-maker-icon="assets/images/gmap/maker.png" data-map-type="ROADMAP" data-map-info="zytheme Company<br>info@zytheme.com" style="width:100%;height:700px;"></div>
            </div>
        </div>
    </div>
</section>
<!-- #google-map end -->

<!-- contact #2
============================================= -->
<?php
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file here
    include("db_connection.php");

    if (isset($_SESSION['customer_email'])) {
        $customer_email = $_SESSION['customer_email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $customer_id_query = "SELECT customer_id FROM customers WHERE customer_email = '$customer_email'";
        $customer_id_result = mysqli_query($conn, $customer_id_query);
        $customer_id_row = mysqli_fetch_assoc($customer_id_result);
        $customer_id = $customer_id_row['customer_id'];

        $insert_contact_query = "INSERT INTO contact (customer_id, subject, message) VALUES ($customer_id, '$subject', '$message')";
        if(mysqli_query($conn, $insert_contact_query)) {
            // Insert successful
            $message = '<div class="alert alert-success" role="alert"><strong>Thank you. We will contact you shortly.</strong></div>';
        } else {
            // Insert failed
            $message = '<div class="alert alert-danger" role="alert"><strong>Sorry, an error occurred. Please try again later.</strong></div>';
        }
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $insert_contact_query = "INSERT INTO contact (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        if(mysqli_query($conn, $insert_contact_query)) {
            // Insert successful
            $message = '<div class="alert alert-success" role="alert"><strong>Thank you. We will contact you shortly.</strong></div>';
        } else {
            // Insert failed
            $message = '<div class="alert alert-danger" role="alert"><strong>Sorry, an error occurred. Please try again later.</strong></div>';
        }
    }
}
?>


<section id="contact2" class="contact contact-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 offset-lg-3">
                <div class="heading heading-2 mb-40 text--center">
                    <h2 class="heading--title">Get In Touch With Us</h2>
                    <p class="heading--desc italic">Quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam
                        voluptatem quia voluptas sit aspernatur aut </p>
                </div>
            </div><!-- .col-md-6 end -->
        </div><!-- .row end -->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <form method="post" class="contactForm mb-0">
                    <div class="row">
                        <?php if (isset($_SESSION['customer_email'])) : ?>
                            <div class="col-sm-12 col-md-12 col-lg-4 offset-lg-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject">
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group mb-80">
                                    <textarea class="form-control" name="message" rows="2" placeholder="Your message here"></textarea>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Enter your name">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group mb-80">
                                    <textarea class="form-control" name="message" rows="2" placeholder="Your message here"></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-sm-12 col-md-12 col-lg-12 text--center">
                            <button type="submit" class="btn btn--primary btn--rounded">SEND TO US</button>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div id="contactResult" class="contact-result"><?php echo $message; ?></div>
                        </div>
                    </div>
                    <!-- .row end -->
                </form>
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>


<?php
include("footer.php");
?>