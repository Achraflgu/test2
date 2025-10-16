<?php
session_start();
include("db_connection.php"); // Include your database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'admin/vendor/autoload.php'; // Include the PHPMailer autoloader

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize the form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postcode = mysqli_real_escape_string($conn, $_POST['postcode']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $order_note = mysqli_real_escape_string($conn, $_POST['order_note']);
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $payment_method_id = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $cart_data = $_POST['cart_data'];

    // Get the customer email from the session
    $customer_email = $_SESSION['customer_email'];

    // Query the database to retrieve the customer ID
    $customer_query = "SELECT customer_id FROM customers WHERE customer_email = '$customer_email'";
    $customer_result = mysqli_query($conn, $customer_query);
    if ($customer_result && mysqli_num_rows($customer_result) > 0) {
        $customer_row = mysqli_fetch_assoc($customer_result);
        $customer_id = $customer_row['customer_id'];

        // Insert the order into the orders table
        $order_date = date('Y-m-d H:i:s');
        // Retrieve the total amount from the HTML
        $totalAmountElement = $_POST['total_amount'];

        // Remove non-numeric characters from the string
        $total_amount = preg_replace('/[^0-9.]/', '', $totalAmountElement);

        $delivery_method_id = mysqli_real_escape_string($conn, $_POST['delivery_method']);
        $delivery_method_query = "SELECT method_name FROM delivery_methods WHERE method_id = '$delivery_method_id'";
        $delivery_method_result = mysqli_query($conn, $delivery_method_query);
        $delivery_method_row = mysqli_fetch_assoc($delivery_method_result);
        $delivery_method_name = $delivery_method_row['method_name'];

        // Now $total_amount contains the numeric value

        // Set payment status based on payment method URL existence
        $payment_status = isset($payment_method_url) ? 'pending-paid' : 'pending-unpaid';
        $invoice_no = uniqid('INV');
        echo $invoice_no;

        // Get the payment method name based on the payment method ID
        $payment_method_query = "SELECT method_name, method_url FROM payment_methods WHERE method_id = '$payment_method_id'";
        $payment_method_result = mysqli_query($conn, $payment_method_query);
        $payment_method_row = mysqli_fetch_assoc($payment_method_result);
        $payment_method_name = $payment_method_row['method_name'];
        $payment_method_url = $payment_method_row['method_url'];

        // Insert the order
        $insert_order_query = "INSERT INTO orders (customer_id, order_date, total_amount, payment_status, invoice_no, order_note) 
                                VALUES ('$customer_id', '$order_date', '$total_amount', '$payment_status', '$invoice_no', '$order_note')";
        $insert_order_result = mysqli_query($conn, $insert_order_query);
        if ($insert_order_result) {
            // Get the order ID
            $order_id = mysqli_insert_id($conn);

            // Clean the cart for the current user's IP address
            $user_ip = $_SERVER['REMOTE_ADDR'];
            $clean_cart_query = "DELETE FROM cart WHERE user_ip = '$user_ip'";
            mysqli_query($conn, $clean_cart_query);

            // If a coupon was used, update its usage count and remove it from the session

            // Insert shipping information into the shipping_info table
            $insert_shipping_query = "INSERT INTO shipping_info (order_id, customer_id, shipping_address, shipping_city, shipping_postal_code, shipping_phone, first_name, last_name)
                                        VALUES ('$order_id', '$customer_id', '$address', '$city', '$postcode', '$phone', '$first_name', '$last_name')";
            mysqli_query($conn, $insert_shipping_query);

            // Insert the payment information into the payments table
            $insert_payment_query = "INSERT INTO payments (order_id, payment_date, amount, payment_method) 
                                        VALUES ('$order_id', '$order_date', '$total_amount', '$payment_method_name')";
            mysqli_query($conn, $insert_payment_query);

            // Insert order details into the order_details table
            $products_list = mysqli_real_escape_string($conn, $_POST['product_list']);
            $subtotal = floatval(preg_replace('/[^0-9.]/', '', $_POST['subtotal']));
            $discount_total = floatval(preg_replace('/[^0-9.]/', '', $_POST['discount_total']));
            $tax_stamp = floatval(preg_replace('/[^0-9.]/', '', $_POST['tax_stamp']));
            $shipping = floatval(preg_replace('/[^0-9.]/', '', $_POST['shipping']));


            $insert_order_details_query = "INSERT INTO order_details (order_id, products_list, subtotal, discount_total, tax_stamp, shipping, total_amount, delivery_method, payment_method) 
                                    VALUES ('$order_id', '$products_list', '$subtotal', '$discount_total', '$tax_stamp', '$shipping', '$total_amount', '$delivery_method_name', '$payment_method_name')";
            mysqli_query($conn, $insert_order_details_query);


            if ($discount_total > 0) {
                if (isset($_SESSION['couponCode'])) {
                    $coupon_code = $_SESSION['couponCode'];
                    
                    // Prepare and execute SELECT query to fetch coupon name based on coupon code
                    $get_coupon_query = "SELECT coupon_name FROM coupons WHERE coupon_code = '$coupon_code'";
                    $result = mysqli_query($conn, $get_coupon_query);
                    
                    // Check if the coupon is found
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Fetch the coupon name from the result
                        $row = mysqli_fetch_assoc($result);
                        $coupon_name = $row['coupon_name'];
                        
                        // Increment the usage count for the coupon
                        $update_coupon_query = "UPDATE coupons SET usage_count = usage_count + 1 WHERE coupon_code = '$coupon_code'";
                        mysqli_query($conn, $update_coupon_query);
                        
                        // Unset and nullify the coupon code in the session
                        unset($_SESSION['couponCode']);
                        $_SESSION['couponCode'] = null;
                        
                        // Now $coupon_name contains the name of the coupon
                        // You can use $coupon_name as needed
                    } else {
                        // Coupon not found in the database
                        // Handle the situation accordingly
                    }
                }
            }
            


            // Insert order items into the orderitems table
            // Iterate over each product
            // Check if the cart data is present and not empty
            if (isset($_POST['cart_data']) && !empty($_POST['cart_data'])) {
                // Decode the cart data
                $cart_data = $_POST['cart_data'];
                $cart_items = json_decode($cart_data, true);

                // Check if decoding was successful and if 'products' key exists
                if ($cart_items && is_array($cart_items) && isset($cart_items['products'])) {
                    // Get the products array
                    $products = $cart_items['products'];

                    // Check if there are products and start from the second one
                    if (count($products) > 1) {
                        for ($i = 1; $i < count($products); $i++) {
                            $item = $products[$i];
                            // Check if 'id' and 'quantity' keys exist for each product
                            if (isset($item['id']) && isset($item['quantity'])) {
                                // Process the product
                                $product_id = $item['id'];
                                $quantity = $item['quantity'];

                                // Extract the numeric value of price from the string
                                $price = isset($item['price']) ? floatval(preg_replace('/[^0-9.]/', '', $item['price'])) : 0; // Default price to 0 if not provided

                                $update_stock_query = "UPDATE products SET product_stock_quantity = product_stock_quantity - $quantity WHERE product_id = '$product_id'";
                                mysqli_query($conn, $update_stock_query);
                                // Insert the order item into the database
                                $insert_order_item_query = "INSERT INTO orderitems (order_id, product_id, quantity, price) 
                            VALUES ('$order_id', '$product_id', '$quantity', '$price')";
                                mysqli_query($conn, $insert_order_item_query);
                            } else {
                                // Handle the case where 'id' or 'quantity' key is missing for a product
                                echo "Error: 'id' or 'quantity' key is missing for a product. Skipping...";
                            }
                        }
                    } else {
                        echo "No products in the cart.";
                    }
                } else {
                    echo "Error: Unable to decode cart data or 'products' key is missing.";
                }
            } else {
                echo "Error: Cart data is missing or empty.";
            }

            // Send two emails - Order Confirmation and In Preparation
            try {
                // Create a new PHPMailer instance for Order Confirmation
                $orderConfirmationMailer = new PHPMailer(true);
                $orderConfirmationMailer->isSMTP();
                // SMTP configuration
                $orderConfirmationMailer->Host = 'smtp.gmail.com';
                $orderConfirmationMailer->SMTPAuth = true;
                $orderConfirmationMailer->Username = 'achrafgu92@gmail.com';
                $orderConfirmationMailer->Password = 'frfpvyagagwfwhju';
                $orderConfirmationMailer->SMTPSecure = 'ssl';
                $orderConfirmationMailer->Port = 465;

                // Sender and recipient
                $orderConfirmationMailer->setFrom('achrafgu92@gmail.com', 'SHADOW FIT');
                $orderConfirmationMailer->addAddress($customer_email, $first_name . ' ' . $last_name);

                // Content
                $orderConfirmationMailer->isHTML(true);
                $orderConfirmationMailer->Subject = 'Order Confirmation';
                ob_start();

                // Include the PHP file which contains the HTML content
                include('orderConfirmationMailer.php');

                // Get the output and clean the buffer
                $htmlContent = ob_get_clean();



                $orderConfirmationMailer->Body =  $htmlContent;
                // Send Order Confirmation email
                $orderConfirmationMailer->send();
            } catch (Exception $e) {
                echo "Error sending Order Confirmation email: {$orderConfirmationMailer->ErrorInfo}";
            }

            try {
                // Create a new PHPMailer instance for In Preparation
                $inPreparationMailer = new PHPMailer(true);
                $inPreparationMailer->isSMTP();
                // SMTP configuration (same as above)
                $inPreparationMailer->Host = 'smtp.gmail.com';
                $inPreparationMailer->SMTPAuth = true;
                $inPreparationMailer->Username = 'achrafgu92@gmail.com';
                $inPreparationMailer->Password = 'frfpvyagagwfwhju';
                $inPreparationMailer->SMTPSecure = 'ssl';
                $inPreparationMailer->Port = 465;

                // Sender and recipient
                $inPreparationMailer->setFrom('achrafgu92@gmail.com', 'SHADOW FIT');
                $inPreparationMailer->addAddress($customer_email, $first_name . ' ' . $last_name);

                // Content
                $inPreparationMailer->isHTML(true);
                $inPreparationMailer->Subject = 'Your Order is in Preparation';
                ob_start();

                // Include the PHP file which contains the HTML content
                include('inPreparationMailer.php');

                // Get the output and clean the buffer
                $htmlContent1 = ob_get_clean();
                // Set the HTML content as the email body
                $inPreparationMailer->Body = $htmlContent1;

                // Send In Preparation email
                $inPreparationMailer->send();
            } catch (Exception $e) {
                echo "Error sending In Preparation email: {$inPreparationMailer->ErrorInfo}";
            }

            // Redirect to the order success page
            exit();
        } else {
            // Error inserting the order
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Customer not found
        echo "Error: Customer not found.";
    }
} else {
    // If the request method is not POST, return an error message
    echo "Error: Invalid request method.";
}
