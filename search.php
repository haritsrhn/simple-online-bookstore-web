<!DOCTYPE html>
<html>

<head>
    <title>Search Result</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><span class="fa fa-shopping-cart"></span> My Cart</a>
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
    <br>
    <main>
        <h4 style="text-align: center;">Search Result</h4>
        <center>
            <hr class="bg-dark" style="width:10em;height:3px;opacity:1">
        </center>
        <br>

        <?php
        session_start();
        // connect to database
        require_once "functions/dbfunctions.php";
        $conn = dbconnect();

        if (isset($_POST['submit-search'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search-input']);
            $sql = "SELECT * FROM books WHERE book_title LIKE '%$search%' OR book_author LIKE '%$search%'";
            $result = mysqli_query($conn, $sql);
            $queryResult = mysqli_num_rows($result);

            echo "<br>";
            echo "<center><h5>There are " . $queryResult . " results</h5></center>";
            echo "<br>";

            if ($queryResult > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<a href='book.php?bookisbn=" . $row['book_isbn'] . "'><div class='book-item'>
              <h3>" . $row['book_title'] . "</h3>
              <p>" . $row['book_author'] . "</p>
              <br>
            </div></a>";
                }
            } else {
                echo "There are no results matching your search !";
            }
        }
        ?>

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