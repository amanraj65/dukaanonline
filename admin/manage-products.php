<?php
session_start();
include('include/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    // Handle stock update
    if (isset($_POST['updateCart'])) {
        $productId = intval($_POST['product_id']);
        $newStock = intval($_POST['stock']);
        
        $sql = "UPDATE products SET stock='$newStock' WHERE id='$productId'";
        if (mysqli_query($con, $sql)) {
            $_SESSION['msg'] = "Stock updated successfully!";
        } else {
            $_SESSION['msg'] = "Failed to update stock!";
        }
        header("location: manage-products.php");
        exit();
    }

    // Handle product deletion
    if (isset($_GET['del'])) {
        mysqli_query($con, "DELETE FROM products WHERE id='" . $_GET['id'] . "'");
        $_SESSION['delmsg'] = "Product deleted successfully!";
        header("location: manage-products.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Manage Products</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <script src="scripts/jquery-1.9.1.min.js"></script>
</head>
<body>
<?php include('include/header.php'); ?>

<div class="wrapper">
    <div class="container">
        <div class="row">
            <?php include('include/sidebar.php'); ?>
            <div class="span9">
                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>Manage Products</h3>
                        </div>
                        <div class="module-body table">
                            
                            <!-- Alert Message -->
                            <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != '') { ?>
                                <div class="alert alert-<?php echo ($_SESSION['msg'] == 'Stock updated successfully!') ? 'success' : 'danger'; ?> alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong><?php echo ($_SESSION['msg'] == 'Stock updated successfully!') ? 'Well done!' : 'Oops!'; ?></strong>
                                    <?php echo htmlentities($_SESSION['msg']); ?>
                                </div>
                                <?php $_SESSION['msg'] = ''; ?>
                            <?php } ?>

                            <?php if (isset($_SESSION['delmsg']) && $_SESSION['delmsg'] != '') { ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Success!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?>
                                </div>
                                <?php $_SESSION['delmsg'] = ''; ?>
                            <?php } ?>

                            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Company</th>
                                        <th>Stock</th>
<th>Stock Threshold</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($con, "SELECT products.*, category.categoryName, subcategory.subcategory, products.stock_threshold 
                                    FROM products 
                                    JOIN category ON category.id = products.category 
                                    JOIN subcategory ON subcategory.id = products.subCategory");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($row['productName']); ?></td>
                                            <td><?php echo htmlentities($row['categoryName']); ?></td>
                                            <td><?php echo htmlentities($row['subcategory']); ?></td>
                                            <td><?php echo htmlentities($row['productCompany']); ?></td>
                                            <td>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <input type="number" name="stock" value="<?php echo htmlentities($row['stock']); ?>" min="0" style="width: 80px;">
        <button type="submit" name="updateCart" class="btn btn-sm btn-primary">Update</button>
    </form>
</td>
<td>
    <span class="badge badge-<?php echo ($row['stock'] < $row['stock_threshold']) ? 'danger' : 'success'; ?>">
        <?php echo htmlentities($row['stock_threshold']); ?>
    </span>
</td>
                                            <td>
                                                <a href="edit-products.php?id=<?php echo $row['id']; ?>"><i class="icon-edit"></i> Edit</a> | 
                                                <a href="manage-products.php?id=<?php echo $row['id']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete?')">
                                                    <i class="icon-remove-sign"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php $cnt++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>

<script src="scripts/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>