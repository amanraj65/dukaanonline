<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {   
    header('location:login.php');
} else {
    if (isset($_POST['submit'])) {
        $userId = $_SESSION['id'];
        $payMethod = $_POST['paymethod'];

        // Update the payment method for orders without a payment method
        $query = "UPDATE orders SET paymentMethod='$payMethod' WHERE userId='$userId' AND paymentMethod IS NULL";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Fetch all orders for this user where payment method was just updated
            $orderQuery = "SELECT productId, quantity FROM orders WHERE userId='$userId' AND paymentMethod='$payMethod'";
            $orderResult = mysqli_query($con, $orderQuery);

            while ($order = mysqli_fetch_assoc($orderResult)) {
                $productId = $order['productId'];
                $quantity = $order['quantity'];

                // Reduce stock in the products table
                $updateStockQuery = "UPDATE products SET stock = stock - $quantity WHERE id='$productId' AND stock >= $quantity";
                mysqli_query($con, $updateStockQuery);
            }

            unset($_SESSION['cart']);
            header('location:order-history.php');
        } else {
            echo "<script>alert('Error updating payment method or stock.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Shopping Portal | Payment Method</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
</head>
<body class="cnt-home">

<header class="header-style-1">
    <?php include('includes/top-header.php');?>
    <?php include('includes/main-header.php');?>
    <?php include('includes/menu-bar.php');?>
</header>

<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="home.php">Home</a></li>
                <li class='active'>Payment Method</li>
            </ul>
        </div>
    </div>
</div>

<div class="body-content outer-top-bd">
    <div class="container">
        <div class="checkout-box faq-page inner-bottom-sm">
            <div class="row">
                <div class="col-md-12">
                    <h2>Choose Payment Method</h2>
                    <div class="panel-group checkout-steps" id="accordion">
                        <div class="panel panel-default checkout-step-01">
                            <div class="panel-heading">
                                <h4 class="unicase-checkout-title">
                                    <a data-toggle="collapse" class="" data-parent="#accordion" href="#collapseOne">
                                        Select your Payment Method
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <form name="payment" method="post">
                                        <input type="radio" name="paymethod" value="COD" checked="checked"> COD
                                        <input type="radio" name="paymethod" value="Internet Banking"> Internet Banking
                                        <input type="radio" name="paymethod" value="Debit / Credit card"> Debit / Credit card
                                        <br><br>
                                        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <?php include('includes/brands-slider.php');?>
    </div>
</div>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>