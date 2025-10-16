<?php
session_start();

include("db_connection.php");
include("header.php");
include("nav.php");

// Retrieve blog data from the database
$sql = "SELECT * FROM blog WHERE status = 1 ORDER BY date_posted DESC";
 // Adjust the query as needed
$result = mysqli_query($conn, $sql);

?>
<section id="page-title" class="page-title mt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-1 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Blog & News</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb breadcrumb-bottom">
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active">Blog & News</li>
                    </ol>
                </div><!-- .title end -->
            </div><!-- .col-md-12 end -->
        </div><!-- .row end -->
    </div><!-- .container end -->
</section><!-- #page-title end -->

<!-- Blog grid 
=========================================-->
<section id="blog" class="blog blog-grid-2 pt-0">
    <div class="container">
        <div class="row">
            <?php
            // Loop through each blog entry
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <!-- Blog Entry -->
                <div class="col-sm-6 col-md-6 col-lg-3 blog-entry filter-chair">
                    <div class="entry--img">
                        <a href="http://localhost/msport/blog.php?blog_id=<?php echo $row['blog_id']; ?>">
                        <img src="<?php echo strpos($row['photo_blog'], 'http') === 0 ? $row['photo_blog'] : 'admin/' . $row['photo_blog']; ?>" alt="entry image" style="height: 300px; width: auto; max-width: 100%; object-fit: cover;" />
                        </a>
                    </div>
                    <div class="entry--content">
                        <div class="entry--meta">
                            <span class="meta--time"><?php echo date('F j, Y', strtotime($row['date_posted'])); ?></span>
                        </div>
                        <div class="entry--title">
                            <h4><a href="http://localhost/msport/blog.php?blog_id=<?php echo $row['blog_id']; ?>"><?php echo $row['name_blog']; ?></a></h4>
                        </div>
                        <div class="entry--footer">
                            <div class="entry--more">
                                <a href="http://localhost/msport/blog.php?blog_id=<?php echo $row['blog_id']; ?>">Read More</a>
                            </div>
                        </div>
                    </div>
                    <!-- .entry-content end -->
                </div>
            <?php
            }
            ?>
        </div>
        <!-- .row end -->
        <div class="row clearfix">
            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                <a href="#" class="btn--more btn--more-1">LOAD MORE</a>
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #blog end -->
<?php
include("footer.php");
?>