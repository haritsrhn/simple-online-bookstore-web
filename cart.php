<?php

session_start();
require_once "functions/dbfunctions.php";
require_once "functions/cart_functions.php";

if (isset($_POST['bookisbn'])) {
    $book_isbn = $_POST['bookisbn'];
}

if (isset($book_isbn)) {
    // new iem selected
    if (!isset($_SESSION['cart'])) {
        // $_SESSION['cart'] is associative array that bookisbn => qty
        $_SESSION['cart'] = array();

        $_SESSION['total_items'] = 0;
        $_SESSION['total_price'] = '0.00';
    }

    if (!isset($_SESSION['cart'][$book_isbn])) {
        $_SESSION['cart'][$book_isbn] = 1;
    } elseif (isset($_POST['cart'])) {
        $_SESSION['cart'][$book_isbn]++;
        unset($_POST);
    }
}

// if save change button is clicked , change the qty of each bookisbn
if (isset($_POST['save_change'])) {
    foreach ($_SESSION['cart'] as $isbn => $qty) {
        if ($_POST[$isbn] == '0') {
            unset($_SESSION['cart']["$isbn"]);
        } else {
            $_SESSION['cart']["$isbn"] = $_POST["$isbn"];
        }
    }
}

// if empty cart button is clicked, unset the whole cart
if (isset($_POST['empty_cart'])) {
    unset($_SESSION['cart']);
    unset($_SESSION['total_items']);
    unset($_SESSION['total_price']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Cart</title>
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
                        <a class="nav-link disabled" href="cart.php"><span class="fa fa-shopping-cart"></span> My
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
        <h4 class="fw-bolder text-center">Cart List</h4>
        <center>
            <hr class="bg-dark" style="width:5em;height:2px;opacity:1">
        </center>
        <?php
        if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
            $_SESSION['total_price'] = total_price($_SESSION['cart']);
            $_SESSION['total_items'] = total_items($_SESSION['cart']);
            ?>
            <div class="card rounded-0 shadow">
                <div class="card-body">
                    <div class="container-fluid">
                        <form action="cart.php" method="post" id="cart-form">
                            <table class="table">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                                <?php
                                foreach ($_SESSION['cart'] as $isbn => $qty) {
                                    $conn = dbconnect();
                                    $book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $book['book_title'] . " by " . $book['book_author']; ?>
                                        </td>
                                        <td><?php echo "$" . $book['book_price']; ?></td>
                                        <td><input type="text" value="<?php echo $qty; ?>" size="2" name="<?php echo $isbn; ?>">
                                        </td>
                                        <td>
                                            <?php echo "$" . $qty * $book['book_price']; ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>
                                        <?php echo $_SESSION['total_items']; ?>
                                    </th>
                                    <th><?php echo "$" . $_SESSION['total_price']; ?></th>

                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input type="submit" class="btn btn-dark rounded-0" name="save_change" value="Save Changes"
                        form="cart-form">
                    <a href="checkout.php" class="btn btn-dark rounded-0">Checkout</a>
                    <a href="listbooks.php" class="btn btn-dark rounded-0">Continue Shopping</a>
                    <input type="submit" class="btn btn-dark rounded-0" name="empty_cart" value="Empty Cart"
                        form="cart-form"
                        onclick="if(confirm('Are you sure to empty your cart?') === false) event.preventDefault()">
                </div>
            </div>

            <?php
        } else {
            ?>
            <div class="alert alert-dark rounded-0">Your cart is empty! Please add at least 1 book to purchase.</div>
        <?php } ?>
    </main>
    <footer class="fixed-bottom bg-dark border py-3 px-2">
        <div class="container">
            <p>Copyright 2021 myBooks</p>
    </footer>
    <div class="clear-fix py-4"></div>
    </div>
</body>

</html>