<?php
require ('connection.php');
require ('auth.php');

if (isset($_POST['logout'])){
  session_destroy();
  header('Location: login.php');
  die();
};

if (isset($_POST['open'])){
  if ($_SESSION['type']=="T"){
    $_SESSION['client'] = $_POST['open'];
    header('Location: client)album.php');
  } else if ($_SESSION['type']=="C"){
    $_SESSION['storyboard'] = $_POST['open'];
    header('Location: open_storyboard.php');     
  };
};

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
} else if ($_SESSION['type'] == "C"){
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

//if submitted, update db
if (isset($_POST['submit'])){

  $named = false;
  $title = stripslashes($_REQUEST['title']);
  if ($title){
    $title = mysqli_real_escape_string($conn,$title);
  } else {
    $i = 1;
    while ($named == false){
      $title = "Untitled" . $i;
      $found = array_search($title, array_column($storyboards, 'title'));

      if ($found){
        $i++;
      } else {
        $named = true;
      }
    }
  };
  
  $frames = $_REQUEST['quantity'];

  $storyboard_id = uniqid();
  
  if ($_SESSION['type'] == "C"){
    $client_id = $_SESSION['id'];
  } else if ($_SESSION['type'] == "T"){
    $client_id = $_SESSION['client'];
  }

    $add_storyboard = "INSERT into `storyboards` (client_id, storyboard_id, no_frames, title)
    VALUES ('$client_id', '$storyboard_id', '$frames', '$title')";

    $result = mysqli_query($conn, $add_storyboard);

    for ($i = 1; $i <= $frames; $i++) {
      $frame_id = uniqid();
      $frame_no = $i;
      $add_frames = "INSERT into `frames` (frame_id, storyboard_id, image_no)
      VALUES ('$frame_id', '$storyboard_id', '$frame_no')";

      $result2 = mysqli_query($conn, $add_frames);
      if (!$result2){
        echo 'frame failed to add';
      }
    }

  if ($result && $result2){
    $_SESSION['storyboard'] = $storyboard_id;
    header('Location: open_storyboard.php');
  } else {
    echo "<div class='form'>
    <h3>Could not create storyboard, please try again.</h3>
    </div>";
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="login.css">
  <title>Document</title>
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
      <?php 
          if ($_SESSION['type']== "T"){
              echo'<a class="nav-btn" href="client_list.php">Home</a>';
          } else if ($_SESSION['type']=="C") {
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
                </form>
                ';   
                $i++;        
              };
              
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

    <section class="text-center container">
        <div class="row py-lg-5">
          <div class="col">
            <h1 class="fw-light">Add a New Storyboard</h1>
          </div>
        </div>
      </section>

  <section class="gradient-form">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4">
  
            <form name="add_storyboard" action="" method="post">
              <p>Enter the storyboard information below:</p>
  

              <div class="form-outline mb-4">
                  <input type="text" name="title" id="form2Example11" class="form-control"
                      placeholder="Optional*"  />
                  <label class="form-label" for="form2Example11">Title or Theme</label>
              </div>

              <div class="form-outline mb-4">
                  <label for="frames">Enter number of frames (between 1 and 5):</label>
                  <input type="number" id="quantity" name="quantity" min="1" max="5" required>
              </div>
  
              <div class="text-center pt-1 mb-5 pb-1">
                  <input type="submit" name="submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">
              </div>
 
            </form>
  
        </div>
      </div>
              
    </div>
  </section>
  <footer class="text-muted py-5">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>
</body>
</html>