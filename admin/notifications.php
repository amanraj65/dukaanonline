<?php
session_start();
include('include/config.php');

if(strlen($_SESSION['alogin']) == 0) {  
    header('location:index.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Notifications</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <script src="scripts/jquery-1.9.1.min.js"></script>
</head>
<body>

<?php include('include/header.php');?>

<div class="wrapper">
    <div class="container">
        <div class="row">
            <?php include('include/sidebar.php');?>

            <div class="span9">
                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>Stock Notifications</h3>
                        </div>
                        <div class="module-body">
                            <?php 
                            // Corrected SQL Query
                            $query = mysqli_query($con, "SELECT * FROM products WHERE stock <= stock_threshold");
                            
                            if(mysqli_num_rows($query) > 0) { 
                            ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Current Stock</th>
                                        <th>Stock Threshold</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    while($row = mysqli_fetch_array($query)) { 
                                    ?>
                                    <tr>
                                        <td><?php echo htmlentities($cnt);?></td>
                                        <td><?php echo htmlentities($row['productName']);?></td>
                                        <td><span class="badge badge-warning"><?php echo htmlentities($row['stock']);?></span></td>
                                        <td><span class="badge badge-danger"><?php echo htmlentities($row['stock_threshold']);?></span></td>
                                        <td><a href="manage-products.php" class="btn btn-primary btn-sm">Update Stock</a></td>
                                    </tr>
                                    <?php $cnt++; } ?>
                                </tbody>
                            </table>
                            <?php } else { ?>
                                <div class="alert alert-success">✅ No products are below the threshold.</div>
                            <?php } ?>
                        </div>
                    </div>
                </div><!--/.content-->
            </div><!--/.span9-->
        </div>
    </div><!--/.container-->
</div><!--/.wrapper-->

<?php include('include/footer.php');?>

<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>