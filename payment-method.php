<?php 
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:login.php');
    exit();
}

if (isset($_POST['submit'])) {
    if (!isset($_POST['paymethod'])) {
        echo "<script>alert('Please select a payment method');</script>";
    } else {
        $userId = $_SESSION['id'];
        $payMethod = $_POST['paymethod'];

        // Fetch all orders for the user where payment is not yet processed
        $orderQuery = "SELECT productId, quantity FROM orders WHERE userId='$userId' AND paymentMethod IS NULL";
        $orderResult = mysqli_query($con, $orderQuery);

        $canProcessOrder = true; // Flag to track stock availability

        // Check stock for each ordered product
        while ($order = mysqli_fetch_assoc($orderResult)) {
            $productId = $order['productId'];
            $quantity = $order['quantity'];

            // Fetch the current stock of the product
            $stockCheckQuery = "SELECT stock FROM products WHERE id='$productId'";
            $stockCheckResult = mysqli_query($con, $stockCheckQuery);
            $stockRow = mysqli_fetch_assoc($stockCheckResult);
            $availableStock = $stockRow['stock'];

            if ($availableStock < $quantity) {
                $canProcessOrder = false; // Not enough stock
                break;
            }
        }

        if ($canProcessOrder) {
            // Update the payment method for the orders
            $updatePaymentQuery = "UPDATE orders SET paymentMethod='$payMethod' WHERE userId='$userId' AND paymentMethod IS NULL";
            $updatePaymentResult = mysqli_query($con, $updatePaymentQuery);

            if ($updatePaymentResult) {
                // Deduct stock for confirmed orders
                mysqli_data_seek($orderResult, 0); // Reset result set pointer
                while ($order = mysqli_fetch_assoc($orderResult)) {
                    $productId = $order['productId'];
                    $quantity = $order['quantity'];

                    $updateStockQuery = "UPDATE products SET stock = stock - $quantity WHERE id='$productId'";
                    mysqli_query($con, $updateStockQuery);
                }

                unset($_SESSION['cart']);
                header('location:order-history.php');
                exit();
            } else {
                echo "<script>alert('Error updating payment method. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Insufficient stock for one or more items in your order. Please update your cart.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Shopping Portal | Payment Method</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <style>
        .payment-option {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-option:hover, .payment-option.selected {
            border-color: #28a745;
            background-color: #e9f7ef;
        }
        .payment-option input {
            display: none;
        }
        .payment-option label {
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .progress-bar {
            width: 0%;
            height: 10px;
            background-color: #28a745;
            transition: width 1s linear;
        }
    </style>
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
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="text-center">Choose Payment Method</h2>
                    <form name="payment" method="post" id="paymentForm">
                        <div class="payment-option">
                            <label for="cod">
                                <input type="radio" name="paymethod" value="COD" id="cod">
                                <span>Cash on Delivery (COD)</span>
                                <i class="fa fa-truck"></i>
                            </label>
                        </div>

                        <div class="payment-option">
                            <label for="netbanking">
                                <input type="radio" name="paymethod" value="Internet Banking" id="netbanking">
                                <span>Internet Banking</span>
                                <i class="fa fa-globe"></i>
                            </label>
                        </div>

                        <div class="payment-option">
                            <label for="creditcard">
                                <input type="radio" name="paymethod" value="Credit Card" id="creditcard">
                                <span>Credit Card</span>
                                <i class="fa fa-credit-card"></i>
                            </label>
                        </div>

                        <div class="payment-option">
                            <label for="debitcard">
                                <input type="radio" name="paymethod" value="Debit Card" id="debitcard">
                                <span>Debit Card</span>
                                <i class="fa fa-university"></i>
                            </label>
                        </div>

                        <br>
                        <input type="submit" value="Proceed" name="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $(".payment-option").click(function() {
            $(".payment-option").removeClass("selected");
            $(this).addClass("selected");
            $(this).find("input").prop("checked", true);
        });

        $("#paymentForm").submit(function(event) {
            var selectedPayment = $("input[name='paymethod']:checked").val();
            if (!selectedPayment) {
                event.preventDefault();
                alert("Please select a payment method.");
            }
        });
    });
</script>
</body>
</html>