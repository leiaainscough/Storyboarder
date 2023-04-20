<?php
require ('connection.php');
require ('auth.php');

if (isset($_POST['logout'])){
  session_destroy();
  header('Location: login.php');
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

if (isset($_POST['open'])){
  $_SESSION['client'] = $_POST['open'];
  header('Location: client_album.php');
  die();
}

//if submitted, update db
if (isset($_POST['submit'])){
  $username = stripslashes($_REQUEST['client_username']);
  $username = mysqli_real_escape_string($conn,$username);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn,$password);
  $forename = stripslashes($_REQUEST['forename']);
  $forename = mysqli_real_escape_string($conn,$forename);
  $surname = stripslashes($_REQUEST['surname']);
  $surname = mysqli_real_escape_string($conn,$surname);

  $client_id = uniqid();
  $user_type = "C";

    $add_user = "INSERT into `users` (client_id, username, password, user_type)
    VALUES ('$client_id', '$username', '$password', '$user_type')";

    $therapist_id = $_SESSION['id'];

    $add_client = "INSERT into `clients` (client_id, forename, surname, therapist_id)
    VALUES ('$client_id', '$forename', '$surname', '$therapist_id')";
    
    $result = mysqli_query($conn, $add_user);
    $result2 = mysqli_query($conn, $add_client);
  if ($result && $result2){
    header('Location: client_list.php');
    die();
  } else {
    echo "<div class='form'>
    <h3>Could not add client, please try again.</h3>
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
    
  <section class="text-center container">
        <div class="row py-lg-5">
          <div class="col">
            <h1 class="fw-light">Add a New Client</h1>
          </div>
        </div>
      </section>

  <section class="gradient-form">
    <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-4">  
                  <form name="add_client" action="" method="post">
                    <p>Enter the client information below:</p>
  

                    <div class="form-outline mb-4">
                      <input type="text" name="forename" id="form2Example11" class="form-control"
                        placeholder="Forename" required />
                      <label class="form-label" for="form2Example11">Forename</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" name="surname" id="form2Example11" class="form-control"
                        placeholder="Surname" required />
                      <label class="form-label" for="form2Example11">Surname</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="text" name="client_username" id="form2Example11" class="form-control"
                        placeholder="Username" required />
                      <label class="form-label" for="form2Example11">Username</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input type="password" name="password" id="form2Example22" class="form-control" required/>
                      <label class="form-label" for="form2Example22">Password</label>
                    </div>

  
                    <div class="text-center pt-1 mb-5 pb-1">
                      <input type="submit" name="submit" value="Add Client" class="btn gradient-custom-2">
                    </div>
                  </form>
                </div>
              </div>
            </div>
    </div>
  </section>
</body>
<footer class="text-muted">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>
</html>