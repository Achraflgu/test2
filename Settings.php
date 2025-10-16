<?php
ob_start();
session_start();

include("db_connection.php");
include("header.php");
include("nav.php");

// Check if session variable is set
if (isset($_SESSION['customer_email'])) {
    // Fetch customer's information from the database
    $customer_email = $_SESSION['customer_email'];
    $sql = "SELECT * FROM customers WHERE customer_email = '$customer_email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $customer_id = $row['customer_id'];
    $order_query = "SELECT * FROM orders WHERE customer_id = '$customer_id' ORDER BY order_id DESC";

    $order_result = mysqli_query($conn, $order_query);

    // If form is submitted to update personal info
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updatePersonalInfo'])) {
        // Update customer information in the database
        $first_name = $_POST['inputFirstName'];
        $email = $_POST['customerEmail'];
        $address = $_POST['customerAddress'];
        $city = $_POST['customerCity'];
        $postal_code = $_POST['customerPostalCode'];
        $country = $_POST['customerCountry'];
        $phone = $_POST['customerPhone'];

        $update_query = "UPDATE customers SET 
                        customer_name = '$first_name', 
                        customer_email = '$email', 
                        customer_address = '$address', 
                        customer_city = '$city', 
                        customer_postal_code = '$postal_code', 
                        customer_country = '$country', 
                        customer_phone = '$phone' 
                        WHERE customer_email = '$customer_email'";

        if (mysqli_query($conn, $update_query)) {
            // Reload customer information after successful update
            $sql = "SELECT * FROM customers WHERE customer_email = '$email'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            // Display success message
            echo "<div class='alert alert-success' role='alert'>Customer information updated successfully!</div>";

            // Update session variable if email is changed
            if ($_SESSION['customer_email'] != $email) {
                $_SESSION['customer_email'] = $email;
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error updating customer information: " . mysqli_error($conn) . "</div>";
        }
    }

    // If form is submitted to update password
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changePassword'])) {
        // Fetch current password from the form
        $currentPassword = $_POST['currentPassword'];

        // Fetch new password and confirm password from the form
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Verify if the current password matches the one in the database
        if ($currentPassword === $row['customer_password']) {
            // Check if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Update the password in the database
                $update_query = "UPDATE customers SET customer_password = '$newPassword' WHERE customer_email = '$customer_email'";

                if (mysqli_query($conn, $update_query)) {
                    // Display success message
                    echo "<div class='alert alert-success' role='alert'>Password updated successfully!</div>";
                } else {
                    // Display error message if update fails
                    echo "<div class='alert alert-danger' role='alert'>Error updating password: " . mysqli_error($conn) . "</div>";
                }
            } else {
                // Display error message if new password and confirm password do not match
                echo "<div class='alert alert-danger' role='alert'>New password and confirm password do not match!</div>";
            }
        } else {
            // Display error message if current password is incorrect
            echo "<div class='alert alert-danger' role='alert'>Current password is incorrect!</div>";
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete'])) {
        // Perform deletion of the account
        $delete_query = "DELETE FROM customers WHERE customer_email = '$customer_email'";
        if (mysqli_query($conn, $delete_query)) {
            // Account deleted successfully
            echo "<div class='alert alert-success' role='alert'>Account deleted successfully!</div>";
            // Redirect the user to the index page after successful deletion
            header("Location: logout.php");
            exit();
        } else {
            // Error deleting account
            echo "<div class='alert alert-danger' role='alert'>Error deleting account: " . mysqli_error($conn) . "</div>";
        }
    }
} else {
    // Redirect to index.php if session variable is not set
    header("Location: index.php");
    exit();
}
?>
<!-- Page Title #1
============================================= -->
<section id="page-title" class="page-title mt-3">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-1 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Settings</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb breadcrumb-bottom">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Settings</li>
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


<section id="register-login" class="register-login pt-30 pb-150  pt-1">
    <div class="container p-0">
        <div class="row">
            <div class="col-md-5 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Profile Settings</h6>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account" role="tab" aria-selected="false">
                            Account
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password" role="tab" aria-selected="true">
                            Password
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#order-history" role="tab" aria-selected="false">
                            History And Details Of My Orders
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#privacy-safety" role="tab">
                            Privacy And Safety
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#delete-account" role="tab">
                            Delete Account
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="account" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Public info</h6>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="inputFirstName">Name</label>
                                                <input type="text" class="form-control" id="inputFirstName" name="inputFirstName" value="<?php echo $row['customer_name']; ?>" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <img alt="<?php echo $row['customer_name']; ?>" src="admin/<?php echo $row['customers_photo']; ?>" class="rounded-circle img-responsive mt-2" width="128" height="128">
                                                <div class="mt-2">
                                                    <span class="btn btn-primary btn-sm">
                                                        <i class="ti-upload"></i> Upload
                                                    </span>
                                                </div>
                                                <small>For best results, use an image at least 128px by 128px in .jpg format</small>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Private info</h6>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="customerEmail">Email</label>
                                        <input type="email" class="form-control" id="customerEmail" name="customerEmail" value="<?php echo $row['customer_email']; ?>" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerAddress">Address</label>
                                        <input type="text" class="form-control" id="customerAddress" name="customerAddress" value="<?php echo $row['customer_address']; ?>" placeholder="1234 Main St">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerCity">City</label>
                                        <input type="text" class="form-control" id="customerCity" name="customerCity" value="<?php echo $row['customer_city']; ?>">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="customerPostalCode">Postal Code</label>
                                            <input type="text" class="form-control" id="customerPostalCode" name="customerPostalCode" value="<?php echo $row['customer_postal_code']; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="customerCountry">Country</label>
                                            <input type="text" class="form-control" id="customerCountry" name="customerCountry" value="<?php echo $row['customer_country']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPhone">Phone</label>
                                        <input type="tel" class="form-control" id="customerPhone" name="customerPhone" value="<?php echo $row['customer_phone']; ?>" placeholder="Phone">
                                    </div>
                                    <button type="submit" class="btn btn--primary btn--rounded" name="updatePersonalInfo">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password</h5>
                                <form method="post">
                                    <div class="form-group">
                                        <label for="inputPasswordCurrent">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="currentPassword" id="inputPasswordCurrent" required>
                                            <div class="input-group-append">
                                                <button class="btn-sm  btn-outline-secondary toggle-password" type="button" data-target="#inputPasswordCurrent">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPasswordNew">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="newPassword" id="inputPasswordNew" required>
                                            <div class="input-group-append">
                                                <button class="btn-sm  btn-outline-secondary toggle-password" type="button" data-target="#inputPasswordNew">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPasswordNew2">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="confirmPassword" id="inputPasswordNew2" required>
                                            <div class="input-group-append">
                                                <button class="btn-sm btn-outline-secondary  toggle-password" type="button" data-target="#inputPasswordNew2">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--primary btn--rounded" name="changePassword">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="tab-pane fade" id="delete-account" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Delete Account</h5>
                                <p>Are you sure you want to delete your account?</p>
                                <form method="post">
                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete your account? This action cannot be undone.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" name="Delete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="privacy-safety" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Privacy and Safety</h5>
                                <p class="lead">At , we take your privacy and safety seriously. Here are some measures we take to ensure a secure shopping experience:</p>
                                <ul class="list-unstyled">
                                    <li><strong>Secure Payment Methods:</strong> We use trusted payment gateways and SSL encryption to protect your financial information.</li>
                                    <li><strong>Data Protection:</strong> Your personal information is kept confidential and will not be shared with third parties.</li>
                                    <li><strong>Privacy Policy:</strong> Read our <a href="privacy-policy.html">privacy policy</a> to understand how we collect, use, and protect your data.</li>
                                    <li><strong>Secure Shopping Environment:</strong> Our website is equipped with security features to safeguard your information during checkout.</li>
                                    <li><strong>Customer Account Security:</strong> Create a strong password for your account and enable two-factor authentication for added security.</li>
                                    <li><strong>Safe Delivery:</strong> We ensure that your orders are delivered safely and securely to your doorstep.</li>
                                    <li><strong>Product Safety Information:</strong> Learn about the proper usage and safety precautions for our sports equipment.</li>
                                    <li><strong>Feedback and Reviews:</strong> Share your feedback and read reviews from other customers about their shopping experience.</li>
                                    <li><strong>Customer Support:</strong> Contact our <a href="contact.html">customer support team</a> for any privacy or safety-related inquiries.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php if (mysqli_num_rows($order_result) > 0) { ?>
                        <div class="tab-pane fade" id="order-history" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Order History and Details</h5>
                                    <div class="table-responsive table-borderless table-hover">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        <div class="toggle-btn">
                                                            <div class="inner-circle"></div>
                                                        </div>
                                                    </th>
                                                    <th>Order Reference</th>
                                                    <th>Date</th>
                                                    <th>Total Price</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body">
                                                <?php
                                                while ($order_row = mysqli_fetch_assoc($order_result)) {
                                                    // Display each order as a table row
                                                    $order_id = $order_row['order_id'];
                                                    $order_date = $order_row['order_date'];
                                                    $total_amount = $order_row['total_amount'];
                                                    $payment_status = $order_row['payment_status'];
                                                    $invoice_no = $order_row['invoice_no'];

                                                    // Fetch payment method from order_details table
                                                    $payment_method_query = "SELECT payment_method FROM order_details WHERE order_id = '$order_id'";
                                                    $payment_method_result = mysqli_query($conn, $payment_method_query);
                                                    $payment_method_row = mysqli_fetch_assoc($payment_method_result);
                                                    $payment_method = ($payment_method_row !== null) ? $payment_method_row['payment_method'] : ''; // Check if payment_method_row is not null
                                                ?>
                                                    <tr class="cell-1">
                                                        <td class="text-center">
                                                            <div class="toggle-btn">
                                                                <div class="inner-circle"></div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo $invoice_no; ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($order_date)); ?></td>
                                                        <td>$<?php echo $total_amount; ?></td>
                                                        <td><?php echo $payment_method; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($payment_status == 'complete') {
                                                                echo '<span class="badge badge-success">Complete</span>';
                                                            } elseif ($payment_status == 'pending-unpaid' || $payment_status == 'pending-paid') {
                                                                echo '<span class="badge badge-warning">Pending</span>';
                                                            } elseif ($payment_status == 'failed') {
                                                                echo '<span class="badge badge-danger">Failed</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-info-circle text-info" data-toggle="modal" data-target="#orderDetailsModal<?php echo $order_id; ?>" style="cursor: pointer;"></i>
                                                        </td>
                                                    </tr>
                                                    <!-- Order Details Modal -->
                                                    <!-- Order Details Modal -->
                                                    <!-- Order Details Modal -->
                                                    <div class="modal model-bg-light fade compare-popup modal-fullscreen" id="orderDetailsModal<?php echo $order_id; ?>" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel<?php echo $order_id; ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="orderDetailsModalLabel<?php echo $order_id; ?>">Order Details - <?php echo $invoice_no; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="cart-total-amount">
                                                                        <h4>Your Order</h4>
                                                                        <div class="cart--products">
                                                                            <h6>Products</h6>
                                                                            <div class="clearfix"></div>
                                                                            <ul class="list-unstyled">
                                                                                <?php
                                                                                // Fetch order details from the database
                                                                                $query = "SELECT * FROM order_details WHERE order_id = $order_id";
                                                                                $result = mysqli_query($conn, $query);
                                                                                if (mysqli_num_rows($result) > 0) {
                                                                                    $order_detail = mysqli_fetch_assoc($result);
                                                                                    echo $order_detail['products_list'];
                                                                                ?>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="cart--subtotal">
                                                                            <h6>Subtotal</h6>
                                                                            <span class="price">$<?php echo $order_detail['subtotal']; ?></span>
                                                                        </div>
                                                                        <div class="cart--discount">
                                                                            <h6 style="font-size: smaller;">Discount Total</h6>
                                                                            <span class="price">$<?php echo $order_detail['discount_total']; ?></span>
                                                                        </div>
                                                                        <div class="cart--Tax border-top-0 pt-0">
                                                                            <h6 style="font-size: smaller;">Tax Stamp</h6>
                                                                            <span class="price">$<?php echo $order_detail['tax_stamp']; ?></span>
                                                                        </div>
                                                                        <div class="cart--shipping border-top-0 pt-0">
                                                                            <h6 style="font-size: smaller;">Shipping</h6>
                                                                            <span class="price">$<?php echo $order_detail['shipping']; ?></span>
                                                                        </div>
                                                                        <div class="cart--total">
                                                                            <div class="clearfix">
                                                                                <h6>Total</h6>
                                                                                <span class="price">$<?php echo $order_detail['total_amount']; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    </div>
                                                                    <!-- .cart-total-amount end -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                        // No orders found for the customer
                        echo "<p>No orders found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordButtons = document.querySelectorAll('.toggle-password');

            togglePasswordButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.querySelector(targetId);

                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        this.innerHTML = '<i class="ti ti-close"></i>';
                    } else {
                        targetInput.type = 'password';
                        this.innerHTML = '<i class="ti ti-eye"></i>';
                    }
                });
            });
        });
    </script>


    <!-- .container end -->
</section>
<!-- #contact2 end -->
<?php
include("footer.php");
ob_end_flush();
?>