<?php
session_start();

$_SESSION['err'] = 1;
foreach ($_POST as $key => $value) {
    if (trim($value) == '') {
        $_SESSION['err'] = 0;
    }
    break;
}

if ($_SESSION['err'] == 0) {
    header("Location: purchase.php");
} else {
    unset($_SESSION['err']);
}

require_once "functions/dbfunctions.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Purchase</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03"
                aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="index.php">myBooks</a>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><span class="fa fa-fw fa-home"></span> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listbooks.php"><span class="fa fa-book"></span> Books</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="cart.php"><span class="fa fa-shopping-cart"></span> My
                            Cart</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="search.php" method="POST">
                    <input class="form-control mr-sm-2" type="search" name="search-input" placeholder="Search Books"
                        aria-label="Search">
                    <button class="btn btn-outline-dark my-2 my-sm-0" type="submit" name="submit-search">Search</button>
                </form>
            </div>
        </nav>
    </header>
    <main>
        <?php
        // connect database
        $conn = dbconnect();
        extract($_SESSION['ship']);

        // validate post section
        $card_number = $_POST['card_number'];
        $card_PID = $_POST['card_PID'];
        $card_expire = strtotime($_POST['card_expire']);
        $card_owner = $_POST['card_owner'];

        // find customer
        $customerid = getCustomerId($name, $address, $city, $zip_code, $country);
        if ($customerid == null) {
            // insert customer into database and return customerid
            $customerid = setCustomerId($name, $address, $city, $zip_code, $country);
        }
        $date = date("Y-m-d H:i:s");
        insertIntoOrder($conn, $customerid, $_SESSION['total_price'], $date, $name, $address, $city, $zip_code, $country);

        // take orderid from order to insert order items
        $orderid = getOrderId($conn, $customerid);

        foreach ($_SESSION['cart'] as $isbn => $qty) {
            $bookprice = getbookprice($isbn);
            $query = "INSERT INTO order_items VALUES 
		('$orderid', '$isbn', '$bookprice', '$qty')";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "Insert value false!" . mysqli_error($conn2);
                exit;
            }
        }

        session_unset();
        ?>
        <div class="alert alert-dark rounded-0 my-4">Your order has been processed sucessfully. We'll be reaching you
            out to confirm your order. Thanks!</div>
    </main>
    <footer class="fixed-bottom bg-dark border py-3 px-2">
        <div class="container">
            <p>Copyright 2021 myBooks</p>
    </footer>
    <div class="clear-fix py-4"></div>
    </div>
</body>

</html>