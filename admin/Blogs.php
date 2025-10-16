<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include_once 'db_connection.php';

// Fetch blogs from the database
$sql = "SELECT blog.*, COUNT(blogreviews.review_id) AS comment_count 
        FROM blog 
        LEFT JOIN blogreviews ON blog.blog_id = blogreviews.blog_id 
        GROUP BY blog.blog_id";
$result = mysqli_query($conn, $sql);

// Check if the form is submitted
if (isset($_POST['editBlogSubmit'])) {
    // Get form data
    $blogId = $_POST['blogId'];
    $blogTitle = $_POST['blogTitle'];
    $blogContent = $_POST['blogContent'];

    // Check if a new photo is uploaded
    if ($_FILES['blogPhoto']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["blogPhoto"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["blogPhoto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["blogPhoto"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        else {
            if (move_uploaded_file($_FILES["blogPhoto"]["tmp_name"], $targetFile)) {
                // Update blog data with new photo
                $sql = "UPDATE blog SET name_blog='$blogTitle', content='$blogContent', photo_blog='$targetFile' WHERE blog_id=$blogId";
                if (mysqli_query($conn, $sql)) {
                    echo "Blog updated successfully.";
                } else {
                    echo "Error updating blog: " . mysqli_error($conn);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // Update blog data without new photo
        $sql = "UPDATE blog SET name_blog='$blogTitle', content='$blogContent' WHERE blog_id=$blogId";
        if (mysqli_query($conn, $sql)) {
            echo "Blog updated successfully.";
            header("Location: http://localhost/msport/admin/index.php?page=Blogs");
exit();
        } else {
            echo "Error updating blog: " . mysqli_error($conn);
        }
    }
}

if (isset($_POST['addBlogSubmit'])) {
    // Get form data
    $blogTitle = $_POST['blogTitle'];
    $blogContent = $_POST['blogContent'];

    // Handle photo upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["blogPhoto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["addBlogSubmit"])) {
        $check = getimagesize($_FILES["blogPhoto"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists


    // Check file size
    if ($_FILES["blogPhoto"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    else {
        if (move_uploaded_file($_FILES["blogPhoto"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["blogPhoto"]["name"])) . " has been uploaded.";
            // Insert the blog post into the database
            $sql = "INSERT INTO blog (name_blog, content, photo_blog, status) VALUES ('$blogTitle', '$blogContent', '$targetFile', 1)";
            if (mysqli_query($conn, $sql)) {
                echo "Blog post added successfully.";
                header("Location: http://localhost/msport/admin/index.php?page=Blogs");
                exit();
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Recent blog post title - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css">
    <style type="text/css">
        .card {
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-subtitle {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .card-body {
            padding: 20px;
        }

        .card-content {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .comment-info {
            color: #6c757d;
            font-size: 0.875rem;
        }

        ol,
        ul {
            padding-left: 0rem;
        }
    </style>
</head>

<body>
    <!-- Add New Blog Modal -->
    <div class="modal fade add-new" tabindex="-1" role="dialog" aria-labelledby="addNewBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewBlogModalLabel">Add New Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add a new blog -->
                    <form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="blogTitle" name="blogTitle">
                        </div>
                        <div class="mb-3">
                            <label for="blogContent" class="form-label">Content</label>
                            <textarea class="form-control" id="blogContent" name="blogContent" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="blogPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="blogPhoto" name="blogPhoto">
                        </div>
                        <button type="submit" class="btn btn-primary" name="addBlogSubmit">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <h5 class="card-title">Blog List <span class="text-muted fw-normal ms-2">(<?php echo mysqli_num_rows($result); ?>)</span></h5>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                    <div>
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="top" title="List"><i class="bx bx-list-ul"></i></a>
                            </li>
                            <li class="nav-item">
                                <a aria-current="page" href="#" class="router-link-active router-link-exact-active nav-link active" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid"><i class="bx bx-grid-alt"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <a href="#" data-bs-toggle="modal" data-bs-target=".add-new" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Add New</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display blogs -->
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <article class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="content-image">
                                <img class="img-thumbnail" src="<?php echo $row['photo_blog']; ?>" alt="Blog Image">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="hf-info">
                                <h2 class="post-title">
                                    <a class="article-link" href="http://localhost/msport/blog.php?blog_id=<?php echo $row['blog_id']; ?>" target="_blank"><?php echo $row['name_blog']; ?></a>
                                </h2>
                                <div class="summary card-content">
                                    <?php echo substr($row['content'], 0, 300) . '...'; ?>
                                </div>
                                <div class="article-actions mt-3">
                                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editBlogModal_<?php echo $row['blog_id']; ?>"><i class="bx bx-edit-alt"></i> Edit</button>
                                    <button class="btn btn-danger btn-sm delete-blog" data-blog-id="<?php echo $row['blog_id']; ?>"><i class="bx bx-trash"></i> Delete</button>
                                    <button class="btn btn-info btn-sm view-blog" data-blog-id="<?php echo $row['blog_id']; ?>" data-status="<?php echo $row['status']; ?>">
                                        <?php if ($row['status'] == 1) : ?>
                                            <i class="bx bx-show"></i> View
                                        <?php else : ?>
                                            <i class="bx bx-hide"></i> Hide
                                        <?php endif; ?>
                                    </button>
                                </div>
                                <div class="comment-info mt-3">
                                    <span><i class="bx bx-message"></i> <?php echo $row['comment_count'] . " Comments"; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Edit Blog Modal -->
            <!-- Edit Blog Modal -->
            <div class="modal fade" id="editBlogModal_<?php echo $row['blog_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editBlogModalLabel_<?php echo $row['blog_id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBlogModalLabel_<?php echo $row['blog_id']; ?>">Edit Blog</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to edit a blog -->
                            <form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="mb-3">
                                    <label for="blogTitle_<?php echo $row['blog_id']; ?>" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="blogTitle_<?php echo $row['blog_id']; ?>" name="blogTitle" value="<?php echo $row['name_blog']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="blogContent_<?php echo $row['blog_id']; ?>" class="form-label">Content</label>
                                    <textarea class="form-control" id="blogContent_<?php echo $row['blog_id']; ?>" name="blogContent" rows="5"><?php echo $row['content']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="currentPhoto_<?php echo $row['blog_id']; ?>" class="form-label">Current Photo</label>
                                    <img src="<?php echo $row['photo_blog']; ?>" id="currentPhoto_<?php echo $row['blog_id']; ?>" class="img-thumbnail" alt="Current Photo" style="max-width: 200px;">
                                </div>
                                <div class="mb-3">
                                    <label for="blogPhoto_<?php echo $row['blog_id']; ?>" class="form-label">New Photo</label>
                                    <input type="file" class="form-control" id="blogPhoto_<?php echo $row['blog_id']; ?>" name="blogPhoto">
                                </div>
                                <input type="hidden" name="blogId" value="<?php echo $row['blog_id']; ?>">
                                <button type="submit" class="btn btn-primary" name="editBlogSubmit">Save changes</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this blog post?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>


        <?php endwhile; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-blog').click(function() {
                var blogId = $(this).data('blog-id');
                var currentStatus = $(this).data('status');
                var newStatus = (currentStatus == 0) ? 1 : 0;

                // Update the status via AJAX
                $.ajax({
                    url: 'Blog_status.php', // Change this to the file that handles status update
                    method: 'POST',
                    data: {
                        blog_id: blogId,
                        status: newStatus
                    },
                    success: function(response) {
                        // Update the button text and status data attribute
                        $('.view-blog[data-blog-id="' + blogId + '"]').data('status', newStatus).html((newStatus == 1) ? '<i class="bx bx-show"></i> View' : '<i class="bx bx-hide"></i> Hide');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-blog').click(function() {
                var blogId = $(this).data('blog-id');
                $('#deleteConfirmationModal').modal('show');
                $('#confirmDeleteBtn').data('blog-id', blogId);
            });

            $('#confirmDeleteBtn').click(function() {
                var blogId = $(this).data('blog-id');
                // Perform AJAX request to delete the blog post
                $.ajax({
                    url: 'delete_blog.php',
                    method: 'POST',
                    data: {
                        blog_id: blogId
                    },
                    success: function(response) {
                        // Handle success
                        console.log('Blog deleted successfully.');
                        location.reload();
                        // You can reload the page or update the UI as needed
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error deleting blog:', error);
                    }
                });
            });
        });
    </script>

</body>

</html>