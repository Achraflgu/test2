<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php'; // Assuming you have this file
$order_id = $_GET['order_id'];

// Fetch order details with associated customer information from the database
// Fetch order details with associated customer information from the database
$sql = "SELECT o.*, c.customer_name, c.customer_address, c.customers_photo, c.customer_city, c.customer_country, c.customer_postal_code,
        CASE 
            WHEN o.payment_status = 'complete' THEN o.tva
            ELSE s.default_tva
        END AS current_tva,
        CASE 
            WHEN o.payment_status = 'complete' THEN o.delivery_charge
            ELSE s.default_delivery_charge
        END AS current_delivery_charge
        FROM orders o
        INNER JOIN customers c ON o.customer_id = c.customer_id
        CROSS JOIN settings s
        WHERE o.order_id = $order_id";


$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result && mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);

    // Extract order and customer information
    $order_id = $order['order_id'];
    $customer_photo = $order['customers_photo'];
    $customer_name = $order['customer_name'];
    $customer_address = $order['customer_address'];
    $customer_city = $order['customer_city'];
    $customer_country = $order['customer_country'];
    $customer_postal_code = $order['customer_postal_code'];
    // Now you can use $order_id, $customer_name, $customer_address, etc. as needed
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Receipt page - Bootdey.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
            body {
                margin-top: 10px;
                background: #eee;
            }
        </style>
    </head>

    <body>
        <div class="container bootdey">
            <div class="row invoice row-printable">
                <div class="col-md-10">
                    <div class="panel panel-default plain" id="dash_0">
                        <div class="panel-body p30">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="invoice-logo"> <img width="100" src="<?php echo $customer_photo; ?>" alt="Customer Photo">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="invoice-from">
                                        <ul class="list-unstyled text-right">
                                            <li>Dash LLC</li>
                                            <li>2500 Ridgepoint Dr, Suite 105-C</li>
                                            <li>Austin TX 78754</li>
                                            <li>VAT Number EU826113958</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="invoice-details mt25">
                                        <div class="well">
                                            <ul class="list-unstyled mb0">
                                                <li><strong>Invoice</strong> #<?php echo $order['invoice_no']; ?></li>
                                                <li><strong>Invoice Date:</strong> <?php echo date('l, F jS, Y', strtotime($order['order_date'])); ?></li>
                                                <!-- You can format the date as desired -->
                                                <!-- Similarly, you can display due date and other details -->
                                                <li><strong>Status:</strong> <span class="label label-danger"><?php echo $order['payment_status']; ?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="invoice-to mt25">
                                        <ul class="list-unstyled">
                                            <li><strong>Invoiced To</strong></li>
                                            <!-- Display customer details from the order -->
                                            <li><?php echo $order['customer_name']; ?></li>
                                            <li><?php echo $order['customer_address']; ?></li>
                                            <li><?php echo $order['customer_city'] . ', ' . $order['customer_country'] . ', ' . $order['customer_postal_code']; ?></li>
                                        </ul>
                                    </div>
                                    <!-- Now you can dynamically generate invoice items based on order items -->
                                    <!-- You can loop through order items and display them -->
                                    <!-- For now, I'm leaving it to you to implement that part -->
                                    <div class="invoice-items">
                                        <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="0">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="per70 text-center">Description</th>
                                                        <th class="per5 text-center">Qty</th>
                                                        <th class="per25 text-center">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Fetch order items associated with the order ID
                                                    $order_id = $_GET['order_id'];
                                                    $sql = "SELECT oi.*, p.product_name, p.product_price 
                        FROM orderitems oi 
                        INNER JOIN products p ON oi.product_id = p.product_id 
                        WHERE oi.order_id = $order_id";
                                                    $result = mysqli_query($conn, $sql);

                                                    // Check if the query was successful
                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        $sub_total = 0;
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $product_name = $row['product_name'];
                                                            $quantity = $row['quantity'];
                                                            $price = $row['price'];

                                                            // Calculate total for the current product
                                                            $total = $quantity * $price;

                                                            // Add the current product total to the sub-total
                                                            $sub_total += $total;
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $product_name; ?></td>
                                                                <td class="text-center"><?php echo $quantity; ?></td>
                                                                <td class="text-center" > <?php echo number_format($total, 3); ?> DT</td>
                                                            </tr>
                                                    <?php
                                                        }
                                                        $total_with_tva_delivery = $sub_total + $order['current_tva'] + $order['current_delivery_charge'];

                                                        // Update the total_amount, tva, and delivery_charge in the order array
                                                        $order['total_amount'] = $total_with_tva_delivery;
                                                        $order['tva'] = $order['current_tva'];
                                                        $order['delivery_charge'] = $order['current_delivery_charge'];

                                                        // Update the total_amount in the database
                                                        if ($order['payment_status'] === 'complete') {
                                                            $order['tva'] = $order['current_tva'];
                                                            $order['delivery_charge'] = $order['current_delivery_charge'];
                                                            
                                                            // Update the total_amount, tva, and delivery_charge in the database
                                                            $update_sql = "UPDATE orders 
                                                                           SET total_amount = $total_with_tva_delivery, 
                                                                               tva = {$order['current_tva']}, 
                                                                               delivery_charge = {$order['current_delivery_charge']} 
                                                                           WHERE order_id = $order_id";
                                                            
                                                            mysqli_query($conn, $update_sql);
                                                        } else {
                                                            // If the order is not 'complete', only update the total_amount
                                                            $update_sql = "UPDATE orders 
                                                                           SET total_amount = $total_with_tva_delivery 
                                                                           WHERE order_id = $order_id";
                                                            
                                                            mysqli_query($conn, $update_sql);
                                                        } }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="text-right">TVA:</th>
                                                        <th class="text-center" > <?php echo number_format($order['current_tva'], 3); ?> DT</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" class="text-right">Delivery Charge:</th>
                                                        <th class="text-center" > <?php echo number_format($order['current_delivery_charge'], 3); ?> DT</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" class="text-right">Total (with TVA and Delivery):</th>
                                                        <th class="text-center" > <?php echo number_format($total_with_tva_delivery, 3); ?> DT</th>

                                                        <!-- Other footer rows -->
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="invoice-footer mt25">
                                        <p class="text-center">Generated on <?php echo date('l, F jS, Y'); ?>
                                            <a href="#" class="btn btn-default ml15" onclick="printInvoice()"><i class="fa fa-print mr5"></i> Print</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>
            function printInvoice() {
                window.print();
            }
        </script>

    </body>

    </html>

<?php
} else {
    // Error handling if order not found
    echo "Order not found.";
}
?>