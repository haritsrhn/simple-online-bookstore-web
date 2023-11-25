<?php
  session_start();
  $book_isbn = $_GET['bookisbn'];
  // connect to database
  require_once "functions/dbfunctions.php";
  $conn = dbconnect();

  $query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
  $result = mysqli_query($conn, $query);
  if(!$result){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  if(!$row){
    echo "Empty book";
    exit;
  }

  $title = $row['book_title'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Details</title>
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
            <a class="navbar-brand" href="index.php">my Books</a>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><span class="fa fa-fw fa-home"></span> Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="listbooks.php"><span class="fa fa-book"></span> Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><span class="fa fa-shopping-cart"></span> My Cart</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="search.php" method="POST">
                    <input class="form-control mr-sm-2" type="search" name="search-input" placeholder="Search Books" aria-label="Search">
                    <button class="btn btn-outline-dark my-2 my-sm-0" type="submit" name="submit-search">Search</button>
                </form>
            </div>
        </nav>
    </header>
    <br>
        <h4 style="text-align: center;">Book Details</h4>
        <center>
        <hr class="bg-dark" style="width:11em;height:3px;opacity:1">
      </center>
        <br>
      <div class="row">
        <div class="col-md-3 text-center book-item">
          <div class="img-holder overflow-hidden">
          <center><img class="img-top" src="images/<?php echo $row['book_image']; ?>"></center>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card rounded-0 shadow">
            <div class="card-body">
              <div class="container-fluid">
                <h4><?= $row['book_title'] ?></h4>
                <hr>
                  <p style="text-align: justify;"><?php echo $row['book_descr']; ?></p>
                  <h4>Details</h4>
                  <table class="table">
                    <?php foreach($row as $key => $value){
                      if($key == "book_descr" || $key == "book_image" || $key == "book_title"){
                        continue;
                      }
                      switch($key){
                        case "book_isbn":
                          $key = "ISBN";
                          break;
                        case "book_title":
                          $key = "Title";
                          break;
                        case "book_author":
                          $key = "Author";
                          break;
                        case "book_price":
                          $key = "Price";
                          break;
                            case "created_at":
                                $key = "Created";
                                break;
                      }
                    ?>
                    <tr>
                      <td><?php echo $key; ?></td>
                      <td><?php echo $value; ?></td>
                    </tr>
                    <?php 
                      } 
                      if(isset($conn)) {mysqli_close($conn); }
                    ?>
                  </table>
                  <form method="post" action="cart.php">
                    <input type="hidden" name="bookisbn" value="<?php echo $book_isbn;?>">
                    <div class="text-center">
                      <input type="submit" value="Add to cart" name="cart" class="btn btn-dark btn-block rounded-0">
                    </div>
                  </form>
              </div>
            </div>
          </div>
       	</div>
      </div>
      <br><br>
      <footer class="fixed-bottom bg-dark border py-3 px-2">
        <div class="container">
            <p>Copyright 2021 myBooks</p>
    </footer>
    <div class="clear-fix py-4"></div>
    </div>
</body>

</html>