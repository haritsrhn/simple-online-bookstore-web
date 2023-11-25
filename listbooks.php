<?php
session_start();
$count = 0;
// connect to database
require_once "functions/dbfunctions.php";
$conn = dbconnect();

$query = "SELECT book_isbn, book_image, book_title FROM books";
$result = mysqli_query($conn, $query);
if (!$result) {
  echo "Can't retrieve data " . mysqli_error($conn);
  exit;
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>List of Books</title>
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
          <li class="nav-item active">
            <a class="nav-link disabled" href="listbooks.php"><span class="fa fa-book"></span> Books</a>
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
    <h4 style="text-align: center;">List of All Books</h4>
    <center>
      <hr class="bg-dark" style="width:14em;height:3px;opacity:1">
    </center>
    <br>
    <?php for ($i = 0; $i < mysqli_num_rows($result); $i++) { ?>
      <div class="row">
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
            <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>"
              class="card rounded-0 shadow book-item text-reset text-decoration-none">
              <div class="img-holder overflow-hidden">
                <center><img class="img-top" src="images/<?php echo $book['book_image']; ?>"></center>
              </div>
              <div class="card-body">
                <div class="card-title fw-bolder h5 text-center">
                  <?= $book['book_title'] ?>
                </div>
              </div>
            </a>
          </div>
          <?php
          $count++;
          if ($count >= 6) {
            $count = 0;
            break;
          }
        } ?>
      </div>
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