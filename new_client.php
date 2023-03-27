<?php
require ('connection.php');
require ('auth.php');

if (isset($_POST['logout'])){
  session_destroy();
  header('Location: login.php');
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

    $t_username = $_SESSION['username'];

    $get_therapist_id = "SELECT client_id FROM `users` WHERE username='$t_username'";
    
    $result = mysqli_query($conn,$get_therapist_id);
          if(mysqli_num_rows($result)==1){
              while ($row = mysqli_fetch_array($result)){
                $therapist_id = $row['client_id'];
              }
          }else{
            echo "<h3>Error</h3>";
          }
    $add_client = "INSERT into `clients` (client_id, forename, surname, therapist_id)
    VALUES ('$client_id', '$forename', '$surname', '$therapist_id')";
    
    $result = mysqli_query($conn, $add_user);
    $result2 = mysqli_query($conn, $add_client);
  if ($result && $result2){
    header('Location: therapist_home.php');
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
  <link rel="stylesheet" href="login.css">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
        <a class="nav-link" href="therapist_home.php">Home <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form method="post" class="form-inline my-2 my-lg-0">
      <button name="logout" class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
    </form>
  </div>
</nav>
    </header>
    

  <section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">
  
                  <div class="text-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                      style="width: 185px;" alt="logo">
                    <h4 class="mt-1 mb-5 pb-1">We are The Lotus Team</h4>
                  </div>
  
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
                      <input type="submit" name="submit" value="Add Client" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">
                    </div>
  
                  </form>
  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>