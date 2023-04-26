<?php 
  //Page adapted from Bootstrap album example: Bootstrap. (n.d.). Bootstrap Album Example. Bootstrap. Retrieved January 13, 2023 from https://getbootstrap.com/docs/4.0/examples/album/

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
  } else {
    $client = $_SESSION['id'];

    $get_storyboards = "SELECT storyboard_id, title FROM `storyboards` WHERE client_id='$client'";
    $result = mysqli_query($conn,$get_storyboards);

    $storyboards = array();
    $i = 0; 
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_array($result)) {
        $storyboards[$i] = $row;
        $i++;
      }
    }    
  }

  $storyboard_id = $_SESSION['storyboard'];

  $get_frames = "SELECT * FROM `frames` WHERE storyboard_id='$storyboard_id'";
  $result = mysqli_query($conn, $get_frames);

  $frames = array();
  $i = 0; 
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $frames[$i] = $row;
      $i++;
    };
  };

  if (isset($_POST['logout'])){
    session_destroy();
    header('Location: login.php');
    die();
  }

  if (isset($_POST['open'])){
    if ($_SESSION['type']=="T"){
      $_SESSION['client'] = $_POST['open'];
      header('Location: client_album.php');
    } else {
      $_SESSION['storyboard'] = $_POST['open'];
      header('Location: open_storyboard.php');     
    }
    die();
  }

  if (isset($_POST['add'])){
    $new_frame = uniqid();

    $no_frames = count($frames);
    $no_frames ++;

    $edit_frame_no = "UPDATE `storyboards` SET no_frames = '$no_frames' WHERE storyboard_id = '$storyboard_id'";
    $result = mysqli_query($conn, $edit_frame_no);

    if (!$result){
      echo 'storyboard db could not be updated';
    }

    $add_frames = "INSERT into `frames` (frame_id, storyboard_id, image_no)
    VALUES ('$new_frame', '$storyboard_id', '$no_frames')";    
    
    $result2 = mysqli_query($conn, $add_frames);
    if (!$result2){
      echo 'frame failed to add';
    }

    header("Refresh:0");
    die();
  };

  if (isset($_POST['view'])){
    $_SESSION['frame'] = $_POST['view'];

    if ($_SESSION['type'] == "T"){
      header('Location: open_frame.php');
    } else if ($_SESSION['type'] == "C"){
      header('Location: index2.php');
    };
    die();
  };


  $get_title = "SELECT title, no_frames FROM `storyboards` WHERE storyboard_id='$storyboard_id'";
  $result = mysqli_query($conn, $get_title);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $title = $row['title'];
      $no_frames = $row['no_frames'];
    }
  };


  if (isset($_POST['delete'])){
    $current_frame = $_POST['delete'];
    $delete_comments = "DELETE from comments WHERE frame_id='$current_frame'";
    $c_delete = mysqli_query($conn,$delete_comments);


    $delete_frames = "DELETE from frames WHERE frame_id = '$current_frame'";
    $f_delete = mysqli_query($conn,$delete_frames);
    header('Refresh:0');
  }
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Storyboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">
    
  </head>
  <body>
  <script src="save_title.js"></script>

    <header>
    <nav class="navbar navbar-expand-lg gradient-custom-2">
    <a class="navbar-brand align-items-center" href="#"><img src="img/nav-logo.png" height="75px"></img></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
      <?php 
          if ($_SESSION['type']== "T"){
              echo'<a class="nav-btn" href="client_list.php">Home</a>';
          } else {
            echo'<a class="nav-btn" href="client_album.php">Home</a>';
          }
        ?>         
        <li class="nav-item">
        <div class="dropdown">
            <?php
            $i= 0;
            if ($_SESSION['type']=="T"){
              echo'                
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                  Clients
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="new_client.php">Add New Client</a></li>';
              foreach ($clients as $row){
                echo '
                <form action="" method="post">
                  <li><button class="dropdown-item" name="open" value=', $clients[$i]['client_id'], '>',$row['forename'], ' ', $row['surname'],'</a></li>
                </form></ul>
                ';   
                $i++;        
              };
              echo '</ul>';
            } else if ($_SESSION['type']=="C") {
              echo'                
              <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                Storyboards
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="new_storyboard.php">Add New Storyboard</a></li>';
              foreach ($storyboards as $row){
                echo '
                <form action="" method="post">
                  <li><button class="dropdown-item" name="open" value=', $storyboards[$i]['storyboard_id'], '>',$row['title'],'</a></li>
                </form>
                ';   
                $i++;        
              };
              echo '</ul>';
            };
            ?>
        </div>
      </li>
    </ul>

    <form method="post" class="form-inline ms-auto">
      <button name="logout" class="btn" type="submit">Logout</button>
    </form>
  </div>
</nav>
    </header>
    
    <main>
    <script src="save_caption.js"></script>

      <section class="py-5 text-center container">
        <div class="row">
          <div class="col-lg-6 col-md-8 mx-auto">
            <?php 
            // editable title with update button calling save title function to write to the database
            echo '
            <h1 id="title" class="fw-light" contenteditable="true">', $title,'</h1>
            <button class="btn btn-sm" onclick="saveTitle(\'',$storyboard_id,'\')">Update Title</button>';
            ?>
            <p class="lead text-muted">Click the thumbnail to view the frame.</p>
          </div>
        </div>
      </section>
      <section class="container-fluid">
      <div class="row justify-content-center">
        <?php
        if ($frames){
          $i = 0;
          foreach ($frames as $row) {
            echo '<div class="col-lg-3">';
            $current_frame = $row['frame'];

            echo '
            <div class="card shadow-sm">';
                if ($current_frame){
                  echo'<img class="bd-placeholder-img card-img-top" width="100%"preserveAspectRatio="xMidYMid slice" focusable="false" src="data:image/png;base64,', $current_frame,'">';
                } else {
                  echo'<svg class="bd-placeholder-img card-img-top" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>';
                };
                echo'

                <div class="card-body">';
                    if ($frames[$i]['caption']){
                      echo '
                      <p class="card-text" id="',$frames[$i]['frame_id'],'" contenteditable="true">', $frames[$i]['caption'],'</p>';
                    } else {
                      echo'
                      <p class="card-text" id="',$frames[$i]['frame_id'],'" contenteditable="true">Add Caption</p>';
                    };
                echo'
                  <div class="d-flex justify-content-between">
                      <button class="btn btn-sm" onclick="saveCaption(\'',$frames[$i]['frame_id'],'\')">Save Caption</button>
                      <form method="post" action="">
                          <button name="view" value="', $frames [$i]['frame_id'],'"  class="btn btn-sm">Open</button>
                      </form>
                      <form action="" method="post">
                        <button name="delete" value=', $frames[$i]['frame_id'],'  class="btn btn-sm">Delete</button>
                      </form>
                  </div>
                </div>
            </div>
           ';
            $i++;
            echo '</div>';
          } 
        }
        ?>
        <div class="col-auto">
          <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title>
            <rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Add a new frame</text></svg>
            <div class="card-body">
              <p class="card-text">Add a Frame</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <form method="post" action="">
                    <button name="add" class="btn btn-sm">New Frame</button>                
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </section>
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
