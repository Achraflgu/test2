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
$sql = "SELECT customers.customer_id, customers.customers_photo, customers.customer_name, 
               customers.customer_address, customers.customer_city, customers.customer_postal_code, 
               customers.customer_country, customers.customer_phone, customers.customer_email, 
               COUNT(orders.order_id) AS order_count
        FROM customers
        LEFT JOIN orders ON customers.customer_id = orders.customer_id
        GROUP BY customers.customer_id";

$result = mysqli_query($conn, $sql);

$countQuery = "SELECT COUNT(*) AS customer_count FROM customers";
$result1 = mysqli_query($conn, $countQuery);

// Check if the query was successful
if ($result1) {
    // Fetch the row
    $row = mysqli_fetch_assoc($result1);
    // Extract the customer count from the fetched row
    $customerCount = $row['customer_count'];
} else {
    // If the query failed, set default count to 0
    $customerCount = 0;
}

// Close the database connection
mysqli_close($conn);

// Close the database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            margin-top: 20px;
            background-color: #eee;
        }

        .project-list-table {
            border-collapse: separate;
            border-spacing: 0 12px
        }

        .project-list-table tr {
            background-color: #fff
        }

        .table-nowrap td,
        .table-nowrap th {
            white-space: nowrap;
        }

        .table-borderless>:not(caption)>*>* {
            border-bottom-width: 0;
        }

        .table>:not(caption)>*>* {
            padding: 0.75rem 0.75rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }

        .avatar-sm {
            height: 2rem;
            width: 2rem;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        img,
        svg {
            vertical-align: middle;
        }

        a {
            color: #3b76e1;
            text-decoration: none;
        }

        .badge-soft-danger {
            color: #f56e6e !important;
            background-color: rgba(245, 110, 110, .1);
        }

        .badge-soft-success {
            color: #63ad6f !important;
            background-color: rgba(99, 173, 111, .1);
        }

        .badge-soft-primary {
            color: #3b76e1 !important;
            background-color: rgba(59, 118, 225, .1);
        }

        .badge-soft-info {
            color: #57c9eb !important;
            background-color: rgba(87, 201, 235, .1);
        }

        .avatar-title {
            align-items: center;
            background-color: #3b76e1;
            color: #fff;
            display: flex;
            font-weight: 500;
            height: 100%;
            justify-content: center;
            width: 100%;
        }

        .bg-soft-primary {
            background-color: rgba(59, 118, 225, .25) !important;
        }

        /* Initially hide the icons */
        .edit-icon,
        .delete-icon {
            display: none;
        }

        /* Show icons when hovering over the row */
        tr:hover .edit-icon,
        tr:hover .delete-icon {
            display: inline;
        }
    </style>
</head>

<body>

    <body>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />

        <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCustomerForm" enctype="multipart/form-data">
                            <input type="hidden" id="customerId" name="customerId">

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="customerName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName">
                                </div>
                                <div class="col">
                                    <label for="customerAddress" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="customerAddress" name="customerAddress">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="customerEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="customerEmail" name="customerEmail" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="customerPhoto" class="form-label">Photo</label>
                                    <div class="input-group">
                                        <img src="" class="img-fluid rounded" id="customerPhoto" alt="Customer Photo">
                                        <input type="hidden" id="customerPhotoInput" name="customerPhoto" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <button type="button" class="btn btn-primary" name="setDefaultPhotoBtn" id="setDefaultPhotoBtn">Set Default Photo</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="mb-3">
                        <h5 class="card-title">Customers List <span class="text-muted fw-normal ms-2">(<?php echo $customerCount; ?>)</span></h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <form class="d-flex">
                        <input id="CusSearchInput" class="form-control mx-auto me-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                        <div>
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a aria-current="page" href="#" class="router-link-active router-link-exact-active nav-link active" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="List" aria-label="List">
                                        <i class="bx bx-list-ul"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Grid" aria-label="Grid"><i class="bx bx-grid-alt"></i></a>
                                </li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-horizontal-rounded"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" style="width: 100%;">
                    <div class>
                        <div class="">
                            <table id="customerTable" class="table table-hover table-responsive project-list-table table-nowrap align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="ps-4" style="width: 50px;">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Postal Code</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Orders</th>
                                        <th scope="col" style="width: 200px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><img src='" . $row['customers_photo'] . "' alt='' class='avatar-sm rounded-circle me-2'></td>";
                                        echo "<td><a href='#' class='text-body'>" . $row['customer_name'] . "</a></td>";
                                        echo "<td>" . $row['customer_address'] . "</td>";
                                        echo "<td>" . $row['customer_city'] . "</td>";
                                        echo "<td>" . $row['customer_postal_code'] . "</td>";
                                        echo "<td><a href='mailto:" . $row['customer_email'] . "'>" . $row['customer_email'] . "</a></td>";
                                        echo "<td>" . $row['customer_phone'] . "</td>";
                                        echo "<td>" . $row['order_count'] . "</td>";
                                        echo "<td class='row-actions'>
        <div class='edit-delete-icons'>
            <a href='#' class='edit-icon' data-toggle='tooltip' title='Edit' data-id='" . $row['customer_id'] . "' data-name='" . $row['customer_name'] . "'><i class='fas fa-edit'></i></a>&nbsp;
            <a href='#' class='delete-icon' data-toggle='tooltip' title='Delete' data-id='" . $row['customer_id'] . "'><i class='far fa-trash-alt'></i></a>
        </div>
    </td>";

                                        echo "</tr>";
                                        $counter++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
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
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
function setupPagination() {
    // Your pagination data
    const totalItems = <?php echo $customerCount; ?>; // Total number of customers
    const itemsPerPage = 10; // Number of customers per page
    const maxPageLinks = 3; // Maximum number of pagination links to display

    // Function to display customers for the given page number
    function displayCustomers(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const tableRows = document.querySelectorAll("#customerTable tbody tr");

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
                displayCustomers(prevPage);
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
                displayCustomers(page);
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
                displayCustomers(nextPage);
                generatePaginationLinks(nextPage);
            }
        });
        paginationLinks.appendChild(nextLi);
    }

    // Initially display first page and generate pagination links
    displayCustomers(1);
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
                // Function to open edit modal with customer data
                $('.edit-icon').click(function() {
                    var customerId = $(this).data('id');

                    // Send an AJAX request to fetch customer data based on the ID
                    $.ajax({
                        url: 'fetch_customer.php',
                        method: 'POST',
                        data: {
                            customerId: customerId
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#customerId').val(response.customer_id);
                            $('#customerName').val(response.customer_name);
                            $('#customerAddress').val(response.customer_address);
                            $('#customerEmail').val(response.customer_email);
                            $('#customerPhoto').attr('src', response.customers_photo);

                            $('#editCustomerModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });

                // Function to handle form submission
                function submitForm() {
                    var formData = new FormData($('#editCustomerForm')[0]);


                    $.ajax({
                        url: 'update_customer.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            $('#editCustomerModal').modal('hide');
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                // Bind form submission to Save changes button click
                $('#saveChangesBtn').click(function(e) {
                    e.preventDefault();
                    submitForm();
                    $('#customerPhoto').val($('#customerPhoto').attr('src'));
                });

                // Bind form submission to form submit event
                $('#editCustomerForm').submit(function(e) {
                    e.preventDefault();
                    submitForm();
                });

                // Function to set default photo
                // Function to set default photo
                $('#setDefaultPhotoBtn').click(function() {
                    $('#customerPhoto').attr('src', 'uploads/default.jpg');
                    // Update form data with default photo
                    $('#customerPhotoInput').val('uploads/default.jpg');
                });
                $('.delete-icon').click(function(e) {
                    e.preventDefault();
                    var customerId = $(this).data('id');
                    var confirmation = confirm('Are you sure you want to delete this customer?');
                    if (confirmation) {
                        // Send AJAX request to delete_customer.php
                        $.ajax({
                            url: 'delete_customer.php',
                            method: 'POST',
                            data: {
                                customerId: customerId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Reload the page or update the table as needed
                                    location.reload(); // Reload the page
                                } else {
                                    alert('Error deleting customer: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });


            });
        </script>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#CusSearchInput').on('input', function() {
                    var searchText = $(this).val().toLowerCase();
                    $('#customerTable tbody tr').each(function() {
                        var name = $(this).find('td:nth-child(2)').text().toLowerCase(); // Customer name column
                        var address = $(this).find('td:nth-child(3)').text().toLowerCase(); // Customer address column
                        var city = $(this).find('td:nth-child(4)').text().toLowerCase(); // Customer city column
                        var postalCode = $(this).find('td:nth-child(5)').text().toLowerCase(); // Customer postal code column
                        var email = $(this).find('td:nth-child(6)').text().toLowerCase(); // Customer email column
                        var phone = $(this).find('td:nth-child(7)').text().toLowerCase(); // Customer phone column
                        if (name.includes(searchText) || address.includes(searchText) || city.includes(searchText) || postalCode.includes(searchText) || email.includes(searchText) || phone.includes(searchText)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            });
        </script>



    </body>

</html>