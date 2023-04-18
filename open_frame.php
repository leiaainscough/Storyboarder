<?php
require 'auth.php';
require 'connection.php';

if ($_SESSION['type'] == "T"){
  $client = $_SESSION['client'];
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
}

$storyboard_id = $_SESSION['storyboard'];
$frame_id = $_SESSION['frame'];

$get_title = "SELECT title, no_frames FROM `storyboards` WHERE storyboard_id='$storyboard_id'";
$result = mysqli_query($conn, $get_title);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $title = $row['title'];
    }
};

$get_frame = "SELECT * FROM `frames` WHERE frame_id='$frame_id'";
$result = mysqli_query($conn, $get_frame);

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_array($result)) {
    $frame = $row;
  };
};

$get_comments = "SELECT * FROM `comments` WHERE frame_id='$frame_id'";
$result = mysqli_query($conn, $get_comments);

$comments = array();
$i = 0; 
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_array($result)) {
    $comments[$i] = $row;
    $i++;
  };
};

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

if (isset($_POST['delete'])){
  $current_id = $_POST['delete'];
  $delete_comments = "DELETE from comments WHERE comment_id='$current_id'";
  $c_delete = mysqli_query($conn,$delete_comments);
  header('Refresh:0');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
    
  </head>
  <body>
    <header>
    <nav class="navbar navbar-expand-lg gradient-custom-2">
    <a class="navbar-brand" href="#"><img src="img/logo.png" height="75px"></img></a>
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
                </form>
                ';   
                $i++;        
              };
            ?>
            </ul>
        </div>
      </li>
    </ul>

    <form method="post" class="form-inline  ms-auto">
      <button name="logout" class="btn" type="submit">Logout</button>
    </form>
  </div>
</nav>
    </header>
    <main>

    <script src="comment.js"></script>
  <div class="container py-4">

    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
      <div class="container-fluid py-5">
        <?php
        echo '<h1 class="fw-light">',$title,'</h1>';

        if ($frame['frame']) {
        echo'<img  src="data:image/png;base64,', $frame['frame'],'"';
        } else {
          echo'<svg xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Frame</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>';
        };
        echo '<p class="col-md-8 fs-4">',$frame['caption'],'</p>';
        ?>
      </div>
    </div>

    <div class="row align-items-md-stretch">
      <div class="col-md-6">
        <div class="h-100 p-5 bg-body-tertiary border rounded-3">
          <h2>Add a Comment</h2>
          <?php
          echo'
            <p><label for="comment">Enter your comment:</label></p>
            <textarea id="comment" name="comment" rows="4" cols="50"></textarea>
            <br>
            <button class="btn btn-sm" onclick="saveComment(\'',$frame_id,'\')">Save Comment</button>';
            ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="h-100 p-5 bg-body-tertiary border rounded-3 overflow-auto">
          <h2>Your Comments</h2>
          <div class="comment-box">
          <?php
            if ($comments){
              $i = 0;
              foreach ($comments as $row){
                echo '<p>',$row['comment_text'],'</p>
                <form action="" method="post">
                  <button name="delete" value=', $row['comment_id'],'  class="btn btn-sm">Delete</button>
                </form>';
                $i++;
              }
            } else {
              echo '<p>You have not made any comments.</p>';
            }
          ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
    <footer class="text-muted py-5">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>    
          
      </body>
</html>
