<?php
  //Page adapted from Bootstrap album example: Bootstrap. (n.d.). Bootstrap Album Example. Bootstrap. Retrieved January 13, 2023 from https://getbootstrap.com/docs/4.0/examples/album/

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


if (isset($_POST['delete'])){
    $client_to_delete = $_POST['delete'];

    $get_storyboards = "SELECT * FROM `storyboards` WHERE client_id='$client_to_delete'";
    $sb_query = mysqli_query($conn,$get_storyboards);

    if (mysqli_num_rows($sb_query) > 0) {
      while($row = mysqli_fetch_assoc($sb_query)) {

        $current_id = $row['storyboard_id'];
        $get_frames = "SELECT * FROM `frames` WHERE storyboard_id='$current_id'";
        $f_query = mysqli_query($conn,$get_frames);

        if (mysqli_num_rows($f_query) > 0) {
          while($id = mysqli_fetch_assoc($f_query)) {

            $current_frame = $id['frame_id'];
            $delete_comments = "DELETE from comments WHERE frame_id='$current_frame'";
            $c_delete = mysqli_query($conn,$delete_comments);
          }
        }

        $delete_frames = "DELETE from frames WHERE storyboard_id = '$current_id'";
        $f_delete = mysqli_query($conn,$delete_frames);

        $delete_storyboard = "DELETE from storyboards WHERE storyboard_id = '$current_id'";
        $s_delete = mysqli_query($conn,$delete_storyboard);

      }
    }

    $delete_client = "DELETE from clients WHERE client_id = '$client_to_delete'";
    $c_delete = mysqli_query($conn,$delete_client);

    $delete_user = "DELETE from users WHERE client_id = '$client_to_delete'";
    $u_delete = mysqli_query($conn,$delete_user);

    header('Refresh:0');

}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">

    
  </head>
  <body>
    <header>
    <nav class="navbar navbar-expand-lg gradient-custom-2">
    <a class="navbar-brand align-items-center" href="#"><img src="img/nav-logo.png" height="75px"></img></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-btn" href="client_list.php">Home</a>
      </li>
      <li class="nav-item">
        <div class="dropdown">
          <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
            Clients
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="new_client.php">Add New Client</a></li>
            <?php
            $i= 0;
              foreach ($clients as $row){
                echo '
                <form action="" method="post">
                  <li><button class="dropdown-item" name="open" value=', $clients[$i]['client_id'], '>',$row['forename'], ' ', $row['surname'],'</a></li>
                </form>';   
                $i++;        
              };

            ?>
          </ul>
        </div>
      </li>
    </ul>

    <form method="post" class="form-inline ms-auto">
      <button name="logout" class="btn" type="submit">Logout</button>
    </form>
  </div>
</nav>
    </header>
    
    <main class="justify-content-center">
      <section class="py-5 text-center container">
        <div class="row">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">My Clients</h1>
            <p class="lead text-muted">Click a client to view their profile</p>
            <p>
              <a href="new_client.php" class="btn">New Client</a>
            </p>
          </div>
        </div>
      </section>
      <section class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-auto">
          <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
            <div class="card-body">
              <p class="card-text">Add a New Client</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="new_client.php" class="btn btn-sm">New Client</a>                
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        if (!$clients) {

        } else {
          $i = 0;
          //for each client the therapist has, loop through and create a card displaying their name, 
          //headed with a piece of their artwork and buttons to open or delete their profile
          foreach ($clients as $row) {
            //create new column
            
            //get a client frame

            
            echo'<div class="col-auto">';
            echo '
            <div class="card shadow-sm">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" 
                aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect 
                width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Add a new client</text></svg>
                <div class="card-body">
                  <p class="card-text">', $clients[$i]['forename'], ' ', $clients[$i]['surname'], '</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <form action="" method="post">
                      <button name="open" value=', $clients[$i]['client_id'], ' class="btn btn-sm" 
                      >Open</button>
                    </form>
                    <form action="" method="post">
                      <button name="delete" value=', $clients[$i]['client_id'],'  class="btn btn-sm">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> ';
            $i++;
          } 
          echo '</div>';
        }?>
      </div>
      </section>
    </main>
    <footer class="text-muted">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>    
          
      </body>
</html>
