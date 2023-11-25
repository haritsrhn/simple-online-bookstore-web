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
    header("Location: checkout.php");
} else {
    unset($_SESSION['err']);
}


$_SESSION['ship'] = array();
foreach ($_POST as $key => $value) {
    if ($key != "submit") {
        $_SESSION['ship'][$key] = $value;
    }
}
require_once "functions/dbfunctions.php";
$conn = dbconnect();
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
        <h4 class="fw-bolder text-center">Payment</h4>
        <center>
            <hr class="bg-dark" style="width:6em;height:2px;opacity:1">
        </center>
        <?php
        if (isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))) {
            ?>
            <div class="card rounded-0 shadow mb-3">
                <div class="card-body">
                    <div class="container-fluid">
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
                                    <td>
                                        <?php echo $qty; ?>
                                    </td>
                                    <td><?php echo "$" . $qty * $book['book_price']; ?></td>
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
                            <tr>
                                <td>Shipping</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>$20</td>
                            </tr>
                            <tr>
                                <th>Total Including Shipping</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>
                                    <?php echo "$" . ($_SESSION['total_price'] + 20); ?>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-8 col-sm-10 col-xs-12">
                    <div class="card rounded-0 shadow">
                        <div class="card-header">
                            <div class="card-title h6 fw-bold">Please fill the following form</div>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <form method="post" action="process.php" class="form-horizontal">
                                    <?php if (isset($_SESSION['err']) && $_SESSION['err'] == 1) { ?>
                                        <p class="text-danger">All fields have to be filled</p>
                                    <?php } ?>
                                    <div class="form-group mb-3">
                                        <label for="card_type" class="control-label">Type</label>
                                        <select class="form-control rounded-0" name="card_type">
                                            <option value="VISA">VISA</option>
                                            <option value="MasterCard">MasterCard</option>
                                            <option value="American Express">American Express</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="card_number" class="control-label">Number</label>
                                        <input type="text" class="form-control rounded-0" name="card_number">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="card_PID" class="control-label">PID</label>
                                        <input type="text" class="form-control rounded-0" name="card_PID">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="card_expire" class="control-label">Expiry Date</label>
                                        <input type="date" name="card_expire" class="form-control rounded-0">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="card_owner" class="control-label">Name</label>
                                        <input type="text" class="form-control rounded-0" name="card_owner">
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-dark btn-block rounded-0">Purchase</button>
                                            <button type="reset"
                                                class="btn btn-block bg-light bg-gradient border rounded-0">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="fw-light font-italic"><small class="text-muted">Please press Purchase to confirm
                                        your purchase, or Continue Shopping to add or remove items.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <p class="text-warning">Your cart is empty! Please make sure you add some books in it!</p>
        <?php } ?>
    </main>
    <br>
    <footer class="fixed-bottom bg-dark border py-3 px-2">
        <div class="container">
            <p>Copyright 2021 myBooks</p>
    </footer>
    <div class="clear-fix py-4"></div>
    </div>
</body>

</html>