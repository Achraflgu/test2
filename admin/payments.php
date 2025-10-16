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

// Query to fetch orders with payment status 'complete' or 'pending-paid' along with their payment details
$sql = "SELECT 
            p.transaction_id,
            o.order_id,
            o.order_date,
            o.invoice_no,
            o.total_amount,
            p.payment_method,
            CASE
                WHEN o.payment_status = 'pending-paid' THEN 'pending'
                ELSE o.payment_status
            END AS payment_status
        FROM 
            orders o
        INNER JOIN 
            payments p ON o.order_id = p.order_id
        WHERE 
            o.payment_status IN ('complete', 'pending-paid')";

// Execute the query
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">


    <title>latest transactions - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            margin-top: 20px;
            background: #FFF5EE;
        }

        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .avatar.sm {
            width: 2.25rem;
            height: 2.25rem;
            font-size: .818125rem;
        }

        .table-nowrap .table td,
        .table-nowrap .table th {
            white-space: nowrap;
        }

        .table>:not(caption)>*>* {
            padding: 0.75rem 1.25rem;
            border-bottom-width: 1px;
        }

        table th {
            font-weight: 600;
            background-color: #eeecfd !important;
        }

        .fa-arrow-up {
            color: #00CED1;
        }

        .fa-arrow-down {
            color: #FF00FF;
        }
    </style>
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

    <div class="container">
        <div class="align-items-center row mb-3">

            <div class="col-md-4">
                <div class="mb-3">
                    <h5 class="card-title">Order Requests List <span class="text-muted fw-normal ms-2">(<?php echo mysqli_num_rows($result); ?>)</span></h5>
                </div>
            </div>

            <div class="col-md-4">
                <form class="d-flex">
                    <input id="paimentsSearchInput" class="form-control mx-auto me-2" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3 mb-lg-5">
                <div class="position-relative card table-nowrap table-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="small text-uppercase bg-body text-muted">
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if there are any rows returned
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each row of result set
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Display data in table row
                                        echo "<tr>";
                                        echo "<td>#{$row['transaction_id']}</td>";
                                        echo "<td>{$row['order_date']}</td>";
                                        echo "<td><a href='view_order_details.php?order_id={$row['order_id']}' target='_blank'>{$row['invoice_no']}</a></td>";
                                        echo "<td>{$row['total_amount']} DT</td>";
                                        echo "<td>{$row['payment_method']}</td>";
                                        if ($row['payment_status'] === 'pending') {
                                            echo "<td><span class='badge fs-6 fw-normal bg-tint-warning text-warning'>" . ucfirst($row['payment_status']) . "</span></td>";
                                        } else {
                                            echo "<td><span class='badge fs-6 fw-normal bg-tint-success text-success'>" . ucfirst($row['payment_status']) . "</span></td>";
                                        }
                                        echo "</tr>";
                                    }
                                } else {
                                    // If no rows returned, display a message
                                    echo "<tr><td colspan='6'>No transactions found.</td></tr>";
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

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            function generatePaginationLinks(currentPage, totalItems) {
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                const paginationLinks = document.getElementById("paginationLinks");

                paginationLinks.innerHTML = ""; // Clear existing links

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
                        generatePaginationLinks(prevPage, totalItems);
                    }
                });
                paginationLinks.appendChild(previousLi);

                // Determine the range of page links to display around the current page
                let startPage = Math.max(1, currentPage - Math.floor(maxPageLinks / 2));
                let endPage = Math.min(totalPages, startPage + maxPageLinks - 1);

                if (endPage - startPage < maxPageLinks - 1) {
                    startPage = Math.max(1, endPage - maxPageLinks + 1);
                }

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
                        generatePaginationLinks(page, totalItems);
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
                        generatePaginationLinks(nextPage, totalItems);
                    }
                });
                paginationLinks.appendChild(nextLi);
            }

            // Initially display first page and generate pagination links
            displayItems(1);
            generatePaginationLinks(1, totalItems);
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
        $('#paimentsSearchInput').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            $('.table tbody tr').each(function() {
                var transactionId = $(this).find('td:nth-child(1)').text().toLowerCase(); // Transaction ID
                var date = $(this).find('td:nth-child(2)').text().toLowerCase(); // Date
                var invoiceNo = $(this).find('td:nth-child(3)').text().toLowerCase(); // Invoice No
                var totalAmount = $(this).find('td:nth-child(4)').text().toLowerCase(); // Total Amount
                var paymentMethod = $(this).find('td:nth-child(5)').text().toLowerCase(); // Payment Method
                var status = $(this).find('td:nth-child(6)').text().toLowerCase(); // Status

                if (transactionId.includes(searchText) || date.includes(searchText) || invoiceNo.includes(searchText) || totalAmount.includes(searchText) || paymentMethod.includes(searchText) || status.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>
</body>
<?php
// Close the database connection
mysqli_close($conn);
?>

</html>