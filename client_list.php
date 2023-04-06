<?php
require 'auth.php';
require 'connection.php';

if (isset($_POST['logout'])){
  session_destroy();
  header('Location: login.php');
  die();
}

if (isset($_POST['open'])){
  $_SESSION['client'] = $_POST['open'];
  header('Location: client_album.php');
  die();
}

$therapist_id = $_SESSION['id'];

$get_clients = "SELECT * FROM `clients` WHERE therapist_id='$therapist_id'";
$result = mysqli_query($conn,$get_clients);

$clients = array();
$i = 0; 
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_array($result)) {
    $clients[$i] = $row;
    $i++;
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.108.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link rel="stylesheet" href="style.css">
    


    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
<link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico">
<meta name="theme-color" content="#712cf9">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
      .selector-for-some-widget {
        box-sizing: content-box;
      }
    </style>

    
  </head>
  <body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form method="post" class="form-inline my-2 my-lg-0">
      <button name="logout" class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
    </form>
  </div>
</nav>
    </header>
    
    <main>

      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">My Clients</h1>
            <p class="lead text-muted">Click a client to view their profile</p>
            <p>
              <a href="new_client.php" class="btn btn-primary my-2">New Client</a>
            </p>
          </div>
        </div>
      </section>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <div class="col">
          <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">Add a New Client</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="new_client.php" class="btn btn-primary my-2">New Client</a>                
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        if (!$clients) {

        } else {
          $i = 0;
          foreach ($clients as $row) {
            echo '
            <div class="card shadow-sm">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" 
                aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect 
                width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
                <div class="card-body">
                  <p class="card-text">', $clients[$i]['forename'], ' ', $clients[$i]['surname'], '</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <form action="" method="post">
                      <button name="open" value=', $clients[$i]['client_id'], ' class="btn btn-sm btn-outline-secondary" 
                      >Open</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> ';
            $i++;
          } 
        }?>
      </div>
    </main>
    <footer class="text-muted py-5">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>
    
    
        <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
          
      </body>
</html>
