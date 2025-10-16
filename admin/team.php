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

// Check if the logged-in user has the admin_job "administrateur"
$email = $_SESSION['email'];
$sql = "SELECT admin_job FROM admins WHERE admin_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($admin_job);
$stmt->fetch();
$stmt->close();

$admin_job = strtolower($admin_job);
$target_role = strtolower("Administrator");

// If the admin_job is not "Administrateur", show a confirmation message
if ($admin_job !== $target_role) {
    echo "<script>
            if (confirm('Sorry, you do not have access. Click OK to return to the homepage.')) {
                window.location.href = 'http://localhost/msport/admin/index.php?page=dashboard';
            } else {
                window.location.href = 'http://localhost/msport/admin/index.php?page=dashboard';
            }
          </script>";
    exit();
}

// Proceed with the rest of your code
$sql = "SELECT * FROM admins";
$result = mysqli_query($conn, $sql);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">


    <title>candidate list with skills rating and location - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 40px;
        }

        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .avatar-md {
            height: 5rem;
            width: 5rem;
        }

        .fs-19 {
            font-size: 19px;
        }

        .primary-link {
            color: #314047;
            -webkit-transition: all .5s ease;
            transition: all .5s ease;
        }

        a {
            color: #02af74;
            text-decoration: none;
        }

        .bookmark-post .favorite-icon a,
        .job-box.bookmark-post .favorite-icon a {
            background-color: #da3746;
            color: #fff;
            border-color: danger;
        }

        .favorite-icon a {
            display: inline-block;
            width: 30px;
            height: 30px;
            font-size: 18px;
            line-height: 30px;
            text-align: center;
            border: 1px solid #eff0f2;
            border-radius: 6px;
            color: rgba(173, 181, 189, .55);
            -webkit-transition: all .5s ease;
            transition: all .5s ease;
        }


        .candidate-list-box .favorite-icon {
            position: absolute;
            right: 22px;
            top: 22px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .bg-soft-secondary {
            background-color: rgba(116, 120, 141, .15) !important;
            color: #74788d !important;
        }

        .mt-1 {
            margin-top: 0.25rem !important;
        }
        .favorite-icon {
    display: none;
}

.candidate-list-box:hover .favorite-icon {
    display: block;
}

    </style>
</head>

<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />

    <div class="modal fade add-new" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">Add New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add a new admin -->
                    <form id="addAdminForm">
                        <div class="mb-3">
                            <label for="addAdminName" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="addAdminName" name="addadminName" placeholder="Enter Admin Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addAdminPhoto" class="form-label">Admin Photo</label>
                            <input type="file" class="form-control" id="addAdminPhoto" name="addAdminPhoto">
                        </div>
                        <div class="mb-3">
                            <label for="addAdminJob" class="form-label">Admin Job</label>
                            <input type="text" class="form-control" id="addAdminJob" name="addadminJob" placeholder="Enter Admin Job">
                        </div>
                        <div class="mb-3">
                            <label for="addAdminEmail" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="addAdminEmail" name="addadminEmail" placeholder="Enter Admin Email">
                        </div>
                        <div class="mb-3">
                            <label for="addAdminPassword" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="addAdminPassword" name="addadminPassword" placeholder="Enter Admin Password">
                        </div>
                        <div class="mb-3">
                            <label for="addAdminPhone" class="form-label">Admin Phone</label>
                            <input type="text" class="form-control" id="addAdminPhone" name="addadminPhone" placeholder="Enter Admin Phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Admin</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to edit admin details -->
                    <form id="editAdminForm">
                        <!-- Hidden input field for adminId -->
                        <input type="hidden" id="adminId" name="adminId">

                        <div class="mb-3">
                            <label for="adminName" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="adminName" name="adminName" placeholder="Enter Admin Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="adminPhoto" class="form-label">Current Admin Photo</label>
                            <img id="currentAdminPhoto" class="img-fluid mb-2" name="currentAdminPhoto" src="" alt="Admin Photo">
                        </div>
                        <div class="mb-3">
                            <label for="newAdminPhoto" class="form-label">New Admin Photo</label>
                            <input type="file" class="form-control" id="newAdminPhoto" name="newAdminPhoto">
                        </div>
                        <div class="mb-3">
                            <label for="adminJob" class="form-label">Admin Job</label>
                            <input type="text" class="form-control" id="adminJob" name="adminJob" placeholder="Enter Admin Job">
                        </div>
                        <div class="mb-3">
                            <label for="adminPhone" class="form-label">Admin Phone</label>
                            <input type="text" class="form-control" id="adminPhone" name="adminPhone" placeholder="Enter Admin Phone">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <section class="section">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-lg-12">
                    <div class="candidate-list-widgets mb-4">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="align-items-center row">

                        <div class="col-md-4">
                            <div class="mb-3">
                                <h5 class="card-title">Team Members List <span class="text-muted fw-normal ms-2">(<?php echo mysqli_num_rows($result); ?>)</span></h5>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <form class="d-flex">
                                <input id="brandSearchInput" class="form-control mx-auto me-2" type="search" placeholder="Search" aria-label="Search">
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
                                <!-- HTML -->
                                <div>
                                    <a href="#" id="addNewAdminButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".add-new">
                                        <i class="bx bx-plus me-1"></i> Add New
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12" id="paginationContent">
                                    <div class="align-items-center row">
                                        <div class="col-md-12">
                                            <div class="candidate-list">
                                                <?php
                                                // Loop through each admin and display their details
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<div class="candidate-list-box card mt-4">
                                            <div class="p-4 card-body">
                                                <div class="align-items-center row">
                                                    <div class="col-auto">
                                                        <div class="candidate-list-images">
                                                            <img src="' . $row['admin_photo'] . '" alt="Admin Photo" class="avatar-md img-thumbnail rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="candidate-list-content mt-3 mt-lg-0">
                                                            <h5 class="fs-19 mb-0">' . $row['admin_name'] . '</h5>
                                                            <p class="text-muted mb-2">@' . strstr($row['admin_email'], '@', true) . '</p>

                                                            <ul class="list-inline mb-0 text-muted">
                                                            <li class="list-inline-item"><i class="mdi mdi-email"></i> ' . $row['admin_email'] . '</li>
                                                            <li class="list-inline-item"><i class="bx bx-phone"></i> ' . $row['admin_phone'] . '</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mt-2 mt-lg-0 d-flex flex-wrap align-items-start gap-1">
                                                            <span class="badge bg-soft-secondary fs-14 mt-1 mr-1">' . $row['admin_job'] . '</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="favorite-icon">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editModal" data-admin-id="' . $row['admin_id'] . '" class="edit-admin-btn">
                                                        <i class="fas fa-edit"></i> <!-- Edit icon -->
                                                    </a>
                                                    <a href="#" data-admin-id="' . $row['admin_id'] . '" class="delete-admin-btn">
                                                        <i class="far fa-trash-alt"></i> <!-- Delete icon -->
                                                    </a>
                                                </div>
                                            </div>
                                        </div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
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
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="mdi mdi-chevron-double-right fs-15"></i></a>
                                </li>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       function setupPagination() {
    // Your pagination data
    const totalItems = <?php echo mysqli_num_rows($result); ?>; // Total number of items
    const itemsPerPage = 4; // Number of items per page
    const maxPageLinks = 3; // Maximum number of pagination links to display

    // Function to display items for the given page number
    function displayItems(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const items = document.querySelectorAll(".candidate-list-box");

        items.forEach(function(item, index) {
            if (index >= startIndex && index < endIndex) {
                item.style.display = "block"; // Show the item
            } else {
                item.style.display = "none"; // Hide the item
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

    // Event delegation for pagination links
    document.getElementById("paginationLinks").addEventListener("click", function(event) {
        event.preventDefault();
        const target = event.target;
        if (target.classList.contains("page-link")) {
            const page = parseInt(target.getAttribute("data-page"));
            if (!isNaN(page)) {
                displayItems(page);
                generatePaginationLinks(page);
            }
        }
    });

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

    <script>
        $(document).ready(function() {
            // When the edit icon is clicked
            $('.edit-admin-btn').click(function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Get the admin ID from the data attribute
                var adminId = $(this).data('admin-id');

                // AJAX request to fetch admin details
                $.ajax({
                    url: 'fetch_admin_details.php',
                    method: 'POST',
                    data: {
                        adminId: adminId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Populate the modal fields with the fetched admin details
                        $('#adminId').val(adminId); // Set the admin ID in the hidden input field
                        $('#adminName').val(response.admin_name);
                        $('#adminJob').val(response.admin_job);
                        $('#adminPhone').val(response.admin_phone);

                        // Display current admin photo
                        $('#currentAdminPhoto').attr('src', response.admin_photo);

                        // Open the modal
                        $('#editModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle errors
                    }
                });
            });

            // When the Save Changes button is clicked
            // When the Save Changes button is clicked
            $('#saveChangesBtn').click(function() {
                // Create FormData object to handle file uploads
                var formData = new FormData($('#editAdminForm')[0]);

                // Add adminId to form data
                var adminId = $('#adminId').val(); // Assuming you have an input field with id "adminId" to store the admin's ID
                formData.append('adminId', adminId);

                // AJAX request to update admin details
                $.ajax({
                    url: 'update_admin_details.php',
                    method: 'POST',
                    data: formData,
                    contentType: false, // Prevent jQuery from automatically setting the Content-Type header
                    processData: false, // Prevent jQuery from processing the data
                    success: function(response) {
                        // Handle success
                        $('#currentAdminPhoto').attr('src', response.newAdminPhotoPath);
                        console.log(response);
                        // Close the modal
                        $('#editModal').modal('hide');
                        location.reload();
                        // Optionally, perform any other action
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle errors
                    }
                });
            });

            $('.delete-admin-btn').click(function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Get the admin ID from the data attribute
                var adminId = $(this).data('admin-id');

                // Confirm deletion with the user
                if (confirm("Are you sure you want to delete this admin?")) {
                    // AJAX request to delete the admin
                    $.ajax({
                        url: 'delete_admin.php',
                        method: 'POST',
                        data: {
                            adminId: adminId
                        },
                        success: function(response) {
                            // Handle success
                            console.log(response);
                            location.reload();
                            // Optionally, reload the admin list or perform any other action
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Handle errors
                        }
                    });
                }
            });

            $('#addAdminForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission behavior

                // Serialize form data
                var formData = new FormData(this);

                // AJAX request to add a new admin
                $.ajax({
                    url: 'add_admin.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success
                        console.log(response);
                        // Optionally, display a success message or perform any other action
                        // Close the modal
                        $('.add-new').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle errors
                        // Optionally, display an error message or perform any other action
                    }
                });
            });
            // Update image source when a new photo is selected
            $('#newAdminPhoto').change(function() {
                var file = $(this).prop('files')[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#currentAdminPhoto').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            });
            // JavaScript / jQuery
            $(document).ready(function() {
                // Listen for click event on the "Add New" button
                $('#addNewAdminButton').click(function(e) {
                    e.preventDefault(); // Prevent default link behavior
                    // Manually trigger the modal to open
                    $('.add-new').modal('show');
                });
            });

        });
    </script>



</body>

</html>