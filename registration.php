<?php
      require ('connection.php');
      //if submitted, update db

      if (isset($_REQUEST['username'])){
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($conn,$username);

        $check_username = "SELECT username from users WHERE username = '$username'";
        $result = mysqli_query($conn, $check_username);
        if ($result){
          echo "<script>alert('Username already exists, please choose another.')</script>";
          header("Refresh:0");
          return;
        }
        
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn,$password);
        $forename = stripslashes($_REQUEST['forename']);
        $forename = mysqli_real_escape_string($conn,$forename);
        $surname = stripslashes($_REQUEST['surname']);
        $surname = mysqli_real_escape_string($conn,$surname);

        $user_type = "T";
        $user_id = uniqid();

          $add_user = "INSERT into `users` (username, password, client_id, user_type)
          VALUES ('$username', '$password', '$user_id', '$user_type')";

          $add_therapist = "INSERT into `therapists` (forename, surname, therapist_id)
          VALUES ('$forename', '$surname', '$user_id')";
          
          $result = mysqli_query($conn, $add_user);
          $result2 = mysqli_query($conn, $add_therapist);
        if ($result && $result2){
          header('Location: login.php');
          die();
        } else {
          echo "<div class='form'>
          <h3>Registration failed, please try again.</h3>
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
  <link rel="stylesheet" href="login.css">

</head>
<body>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <header>
    <nav class="navbar navbar-expand-lg gradient-custom-2">
    <a class="navbar-brand align-items-center" href="#"><img src="img/nav-logo.png" height="75px"></img></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
      </li>
    </ul>
  </div>
</nav>
    </header>

  <section class="gradient-form">
    <div class="container py-5">
      <div class="row d-flex justify-content-center align-items-center">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">
  
                  <div class="text-center">
                  <a class="navbar-brand" href="#"><img src="img/nav-logo.png" width="185px"></img></a>
                  </div>
  
                  <form name="registration" action="" method="post">
                    <p>Enter the form below:</p>
  

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
                      <input type="text" name="username" id="form2Example11" class="form-control"
                        placeholder="Username" required />
                      <label class="form-label" for="form2Example11">Username</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input type="password" name="password" id="form2Example22" class="form-control" required/>
                      <label class="form-label" for="form2Example22">Password</label>
                    </div>

  
                    <div class="text-center pt-1 mb-5 pb-1">
                      <input type="submit" name="submit" value="Register" class="btn gradient-custom-2 mb-3">
                    </div>
  
                  </form>
  
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">Paint Your Story</h4>
                  <p class="small mb-0">Paint Your Story is a collaborative and unique application which grants clients the freedom to tell stories in a new way. The application aims to be a choice of medium in art therapy sessions, to enable clients to find their voice through experimentation with digital art.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
      <br>
      <br>
  <footer class="text-muted py-5">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>
</body>
</html>