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

// Fetch order details
$sql = "SELECT o.order_id, o.order_date, o.total_amount, o.payment_status, o.invoice_no, c.customer_name, c.customer_email, c.customer_id, c.customer_phone, c.customers_photo,
                GROUP_CONCAT(CONCAT(oi.quantity, ' ', p.product_name) SEPARATOR ', ') AS order_items
        FROM orders o
        INNER JOIN customers c ON o.customer_id = c.customer_id
        INNER JOIN orderitems oi ON o.order_id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.product_id
        GROUP BY o.order_id";
$result = mysqli_query($conn, $sql);

// Check if query executed successfully
if ($result) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">


        <title>Booking Requests list - Bootdey.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style type="text/css">
            body {
                background: #f6f9fc;
                margin-top: 20px;
            }

            .bg-light-blue {
                background-color: #e9f7fe !important;
                color: #3184ae;
                padding: 1px 18px;
                border-radius: 4px;
            }

            .bg-light-green {
                background-color: rgba(40, 167, 69, 0.2) !important;
                padding: 1px 18px;
                border-radius: 4px;
                color: #28a745 !important;
            }

            .bg-light-red {
                background-color: rgba(220, 53, 69, 0.2) !important;
                padding: 1px 18px;
                border-radius: 4px;
                color: #dc3545 !important;
            }

            .bg-dark-green {
                background-color: rgba(40, 167, 69, 0.8) !important;
                padding: 1px 18px;
                border-radius: 4px;
                color: #28a745 !important;
            }


            .buttons-to-right {
                position: absolute;
                right: 0;
                top: 40%;
            }

            .btn-gray {
                color: #666;
                background-color: #eee;
                padding: 7px 18px;
                border-radius: 4px;
            }

            .booking:hover .buttons-to-right .btn-gray {
                opacity: 1;
                transition: .3s;
            }

            .buttons-to-right .btn-gray {
                opacity: 0;
                transition: .3s;
            }

            .btn-gray:hover {
                background-color: #36a3f5;
                color: #fff;
            }

            .booking {
                margin-bottom: 30px;
                border-bottom: 1px solid #eee;
                padding-bottom: 30px;
            }

            .booking:last-child {
                margin-bottom: 0px;
                border-bottom: none;
                padding-bottom: 0px;
            }

            @media screen and (max-width: 575px) {
                .buttons-to-right {
                    top: 10%;
                }

                .buttons-to-right a {
                    display: block;
                    margin-bottom: 20px;
                }

                .buttons-to-right a:last-child {
                    margin-bottom: 0px;
                }

                .bg-light-blue,
                .bg-light-green,
                .bg-light-red,
                .bg-dark-green,
                .btn-gray {
                    padding: 7px;
                }
            }

            .card {
                margin-bottom: 20px;
                background-color: #fff;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
                border-radius: 4px;
                box-shadow: none;
                border: none;
                padding: 25px;
            }

            .mb-5,
            .my-5 {
                margin-bottom: 3rem !important;
            }

            .msg-img {
                margin-right: 20px;
            }

            .msg-img img {
                width: 60px;
                height: auto;
                border-radius: 50%;
                max-height: 60px;
                /* Add this line */
            }

            img {
                max-width: 100%;
                height: auto;
            }

            .order-details {
                display: flex;
                align-items: center;
            }

            .order-items {
                margin-left: 20px;
                /* Adjust the indentation as needed */
            }

            .table-hoverable tbody tr:hover td {
                position: relative;
            }

            .table-hoverable tbody tr:hover .action-icon {
                display: inline-block;
            }

            .action-icon {
                display: none;
            }
        </style>
    </head>

    <body>
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
        <div class="container">
            <div class="col-md-12">
                <div class="align-items-center row">

                    <div class="col-md-4">
                        <div class="mb-3">
                            <h5 class="card-title">Order Requests List <span class="text-muted fw-normal ms-2">(<?php echo mysqli_num_rows($result); ?>)</span></h5>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <form class="d-flex">
                            <input id="ordersSearchInput" class="form-control mx-auto me-2" type="search" placeholder="Search" aria-label="Search">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 table-responsive" style="width: 100%;">
                    <table class="table table-hover table-hoverable mt-3">
                        <thead class="">
                            <tr class="thead-light">
                                <th>Customer Order</th>
                                <th>Invoice</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through each order
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['customer_email']}</td>";
                                echo "<td><a href='view_order_details.php?order_id={$row['order_id']}' target='_blank'>{$row['invoice_no']}</a></td>";
                                echo "<td>{$row['order_date']}</td>";
                                echo "<td>{$row['total_amount']} DT</td>";
                                echo "<td>";
                                // Display payment status with color coding
                                switch ($row['payment_status']) {
                                    case 'pending-paid':
                                    case 'pending-unpaid':
                                        echo "<span class='badge badge-primary'>Pending</span>";
                                        break;
                                    case 'complete':
                                        echo "<span class='badge badge-success'>Complete</span>";
                                        break;
                                    default:
                                        echo htmlspecialchars($row['payment_status']);
                                        break;
                                }
                                echo "</td>";

                                echo "<td>";
                                if ($row['payment_status'] === 'pending-paid' || $row['payment_status'] === 'pending-unpaid') {
                                    echo "<i class='fas fa-eye action-icon' data-toggle='modal' data-target='#orderModal{$row['order_id']}' style='cursor: pointer;'></i>";
                                } else {
                                    // Use a conditional to determine which icon to display based on the order's status
                                    $iconClass = ($row['payment_status'] === 'complete') ? 'fas fa-check' : 'fas fa-times';
                                    echo "<i class='{$iconClass} action-icon' data-toggle='modal' data-target='#orderModal{$row['order_id']}' style='cursor: pointer;'></i>";
                                }
                                echo "</td>";
                                echo "</tr>";

                                // Modal for order details
                                echo "<div class='modal fade' id='orderModal{$row['order_id']}' tabindex='-1' role='dialog' aria-labelledby='orderModalLabel{$row['order_id']}' aria-hidden='true'>";
                                echo "<div class='modal-dialog modal-lg' role='document'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<h5 class='modal-title' id='orderModalLabel{$row['order_id']}'>Order Details</h5>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                                echo "<span aria-hidden='true'>&times;</span>";
                                echo "</button>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                // Insert your card layout here
                                echo "<div class='card card-white'>";
                                echo "<div class='card-body'>";
                                echo "<ul class='list-unstyled'>";
                                echo "<li class='position-relative booking'>";
                                echo "<div class='media'>";
                                echo "<div class='msg-img'>";
                                echo "<img src='{$row['customers_photo']}' alt=''>";
                                echo "</div>";
                                echo "<div class='media-body'>";
                                echo "<h5 class='mb-4'>{$row['customer_name']}";
                                // Display payment status badge
                                $payment_status_class = '';
                                $price_bg_class = '';
                                switch ($row['payment_status']) {
                                    case 'pending-unpaid':
                                        echo "<span class='badge badge-primary mx-3'>Pending</span>";
                                        echo "<span class='badge badge-danger'>Unpaid</span>";
                                        $payment_status_class = 'bg-light-blue';
                                        $price_bg_class = 'bg-light-red'; // No payment made
                                        break;
                                    case 'pending-paid':
                                        echo "<span class='badge badge-primary mx-3'>Pending</span>";
                                        echo "<span class='badge badge-success'>Paid</span>";
                                        $payment_status_class = 'bg-light-blue';
                                        $price_bg_class = 'bg-light-green'; // Payment made
                                        break;
                                    case 'complete':
                                        echo "<span class='badge badge-success mx-3'>Complete</span>";
                                        $payment_status_class = 'bg-light-green';
                                        $price_bg_class = 'bg-light-green';
                                        break;
                                    case 'failed':
                                        echo "<span class='badge badge-danger mx-3'>Failed</span>";
                                        $payment_status_class = 'bg-light-red';
                                        $price_bg_class = 'bg-light-red';
                                        break;
                                    default:
                                        echo "<span class='badge badge-info'>" . htmlspecialchars($row['payment_status']) . "</span>";
                                        $payment_status_class = 'bg-light-blue';
                                        $price_bg_class = 'bg-light-red'; // Default to unpaid
                                        break;
                                }
                                echo "</h5>";
                                echo "<div class='mb-3'>";
                                echo "<span class='mr-2 d-block d-sm-inline-block mb-2 mb-sm-0'>Order Date:</span>";
                                echo "<span class='{$payment_status_class}'>{$row['order_date']}</span>";
                                echo "</div>";
                                echo "<div class='mb-3'>";
                                echo "<div class='order-details'>";
                                echo "<span class='mr-2 d-block d-sm-inline-block mb-2 mb-sm-0'>Order Details:</span>";
                                echo "<div class='order-items'>";
                                $order_items = explode(',', $row['order_items']);
                                foreach ($order_items as $item) {
                                    echo "<span class='{$payment_status_class}'>" . htmlspecialchars($item) . "</span><br>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='mb-3'>";
                                echo "<span class='mr-2 d-block d-sm-inline-block mb-2 mb-sm-0'>Price:</span>";
                                echo "<span class='{$price_bg_class}'>{$row['total_amount']} DT</span>";
                                echo "</div>";
                                echo "<div class='mb-3'>";
                                echo "<span class='mr-2 d-block d-sm-inline-block mb-2 mb-sm-0'>Invoice No:</span>";
                                echo "<span class='{$payment_status_class}'>{$row['invoice_no']}</span>";
                                echo "</div>";
                                echo "<div class='mb-5'>";
                                echo "<span class='mr-2 d-block d-sm-inline-block mb-1 mb-sm-0'>Customers:</span>";
                                echo "<span class='border-right pr-2 mr-2'>{$row['customer_name']}</span>";
                                echo "<span class='border-right pr-2 mr-2'>{$row['customer_email']}</span>";
                                echo "<span>{$row['customer_phone']}</span>";
                                echo "</div>";
                                echo "<button class='btn btn-primary send-message-btn' data-toggle='modal' data-target='#composeEmailModal' data-customerid='{$row['customer_id']}' data-orderid='{$row['order_id']}'>Send Message</button>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='buttons-to-right'>";
                                if ($row['payment_status'] === 'pending-paid') {
                                    echo "<a href='#' class='btn-gray mr-2 approve-btn' data-orderid='{$row['order_id']}'><i class='far fa-check-circle mr-2'></i> Approve</a>";
                                } elseif ($row['payment_status'] === 'pending-unpaid') {
                                    echo "<a href='#' class='btn-gray mr-2 approve-btn' data-orderid='{$row['order_id']}'><i class='far fa-check-circle mr-2'></i> Approve</a>";
                                    echo "<a href='#' class='btn-gray reject-btn' data-orderid='{$row['order_id']}'><i class='far fa-times-circle mr-2'></i> Reject</a>";
                                }
                                echo "</div>";
                                echo "</li>";
                                echo "</ul>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="row">
                <div class="mt-4 pt-2 col-lg-12">
                    <ul class="pagination justify-content-center" id="paginationLinks">
                        <li class="page-item disabled">
                            <a class="page-link" tabindex="-1" href="#"><i class="mdi mdi-chevron-double-left fs-15"></i></a>
                        </li>
                        <!-- Pagination links will be dynamically generated here -->
                        <li class="page-item">
                            <a class="page-link" href="#"><i class="mdi mdi-chevron-double-right fs-15"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add a modal for composing the email -->
        <div class="modal fade" id="composeEmailModal" tabindex="-1" role="dialog" aria-labelledby="composeEmailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="composeEmailModalLabel">Compose Email</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="send_email.php" method="post">
                            <input type="hidden" id="customerId" name="customerId">
                            <input type="hidden" id="orderId" name="orderId">
                            <div class="form-group">
                                <label for="emailSubject">Subject</label>
                                <input type="text" class="form-control" id="emailSubject" name="emailSubject">
                            </div>
                            <div class="form-group">
                                <label for="emailBody">Message</label>
                                <textarea class="form-control" id="emailBody" name="emailBody" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function setupPagination() {
                // Your pagination data
                const totalItems = <?php echo mysqli_num_rows($result); ?>; // Total number of items
                const itemsPerPage = 10; // Number of items per page
                const maxPageLinks = 3; // Maximum number of pagination links to display

                // Function to display items for the given page number
                function displayItems(page) {
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
                    const tableRows = document.querySelectorAll(".table tbody tr");

                    tableRows.forEach(function(row, index) {
                        if (index >= startIndex && index < endIndex) {
                            row.style.display = "table-row"; // Show the table row
                        } else {
                            row.style.display = "none"; // Hide the table row
                        }
                    });
                }

                // Function to generate pagination links
                function generatePaginationLinks(currentPage) {
                    const totalPages = Math.ceil(totalItems / itemsPerPage);
                    const paginationLinks = document.getElementById("paginationLinks");

                    paginationLinks.innerHTML = ""; // Clear existing links

                    // Determine the range of page links to display around the current page
                    const startPage = Math.max(1, currentPage - Math.floor(maxPageLinks / 2));
                    const endPage = Math.min(totalPages, startPage + maxPageLinks - 1);

                    // Create "Previous" link
                    const previousLi = document.createElement("li");
                    previousLi.classList.add("page-item");
                    previousLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">
                            <i class="mdi mdi-chevron-double-left fs-15"></i>
                        </a>`;
                    previousLi.addEventListener("click", function(event) {
                        event.preventDefault();
                        const prevPage = currentPage - 1;
                        if (prevPage >= 1) {
                            displayItems(prevPage);
                            generatePaginationLinks(prevPage);
                        }
                    });
                    paginationLinks.appendChild(previousLi);

                    // Create page links
                    for (let i = startPage; i <= endPage; i++) {
                        const li = document.createElement("li");
                        li.classList.add("page-item");
                        if (i === currentPage) {
                            li.classList.add("active");
                        }
                        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                        li.addEventListener("click", function(event) {
                            event.preventDefault();
                            const page = parseInt(event.target.getAttribute("data-page"));
                            displayItems(page);
                            generatePaginationLinks(page);
                        });
                        paginationLinks.appendChild(li);
                    }

                    // Create "Next" link
                    const nextLi = document.createElement("li");
                    nextLi.classList.add("page-item");
                    nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">
                        <i class="mdi mdi-chevron-double-right fs-15"></i>
                    </a>`;
                    nextLi.addEventListener("click", function(event) {
                        event.preventDefault();
                        const nextPage = currentPage + 1;
                        if (nextPage <= totalPages) {
                            displayItems(nextPage);
                            generatePaginationLinks(nextPage);
                        }
                    });
                    paginationLinks.appendChild(nextLi);
                }

                // Initially display first page and generate pagination links
                displayItems(1);
                generatePaginationLinks(1);
            }

            // Call setupPagination function based on the page loading method
            // If using DOMContentLoaded
            document.addEventListener("DOMContentLoaded", function() {
                setupPagination();
            });

            // If using window.onload
            window.onload = function() {
                setupPagination();
            };

            // If using inline execution
            setupPagination();
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#ordersSearchInput').on('input', function() {
                    var searchText = $(this).val().toLowerCase();
                    $('.booking').each(function() {
                        var orderDate = $(this).find('.bg-light-blue').text().toLowerCase(); // Order date
                        var orderDetails = $(this).find('.order-items').text().toLowerCase(); // Order details
                        var price = $(this).find('.bg-light-red').text().toLowerCase(); // Price
                        var invoiceNo = $(this).find('.bg-light-blue').text().toLowerCase(); // Invoice number
                        var customerName = $(this).find('.border-right').first().text().toLowerCase(); // Customer name
                        var customerEmail = $(this).find('.border-right').eq(1).text().toLowerCase(); // Customer email
                        var customerPhone = $(this).find('.mb-5 span').last().text().toLowerCase(); // Customer phone

                        if (orderDate.includes(searchText) || orderDetails.includes(searchText) || price.includes(searchText) || invoiceNo.includes(searchText) || customerName.includes(searchText) || customerEmail.includes(searchText) || customerPhone.includes(searchText)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
                // Handle Approve button click
                $('.approve-btn').click(function(e) {
                    e.preventDefault();
                    var orderId = $(this).data('orderid');
                    updateOrderStatus(orderId, 'complete');
                });

                // Handle Reject button click
                $('.reject-btn').click(function(e) {
                    e.preventDefault();
                    var orderId = $(this).data('orderid');
                    updateOrderStatus(orderId, 'failed');
                });

                // Function to update order status via AJAX
                function updateOrderStatus(orderId, status) {
                    $.ajax({
                        url: 'update_order.php', // Change this to the actual URL of your PHP script
                        type: 'POST',
                        data: {
                            order_id: orderId,
                            status: status
                        },
                        success: function(response) {
                            // Handle success response
                            console.log('Order status updated successfully');
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.error('Error updating order status:', error);
                        }
                    });
                }
            });
        </script>

        <script>
            // JavaScript to handle sending message
            $(document).ready(function() {
                $('.send-message-btn').click(function() {
                    var customerId = $(this).data('customerid');
                    var orderId = $(this).data('orderid');
                    $('#customerId').val(customerId);
                    $('#orderId').val(orderId);
                });
            });
        </script>
    </body>

    </html>
<?php
} else {
    // Query execution failed
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>