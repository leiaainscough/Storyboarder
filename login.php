<?php
    require('connection.php');
    session_start();
    
    if (isset($_POST['reg'])){
      header('Location: registration.php');
      die();
    };

    // If form submitted, insert values into the database.
    if (isset($_POST['username'])){
            // removes backslashes
      $username = stripslashes($_REQUEST['username']);
            //escapes special characters in a string
      $username = mysqli_real_escape_string($conn,$username);
      $password = stripslashes($_REQUEST['password']);
      $password = mysqli_real_escape_string($conn,$password);
      //Checking is user existing in the database or not
      $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
      $result = mysqli_query($conn,$query);
      //$rows = mysqli_num_rows($result);
      if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_assoc($result);
          $_SESSION['id'] = $row['client_id'];
          $_SESSION['type'] = $row['user_type'];
          if ($_SESSION['type'] == "C"){
            header("Location: client_album.php");
          } else if ($_SESSION['type'] == "T") {
            header("Location: client_list.php");
          }
          // Redirect user to index.php        
      }else{
          echo "<script>alert('Incorrect username or password, please try again')</script>";
      }
    }
  ?> 
  

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
  
                  <section class="text-center">
                    <img src="img/nav-logo.png" width="200px"></img>
                  </section>
  
                  <form name="login" action="" method="post">
                    <p>Please login to your account</p>
  
                    <div class="form-outline mb-4">
                      <input type="text" name="username" id="form2Example11" class="form-control"
                        placeholder="Username" required/>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input type="password" name="password" id="form2Example22" class="form-control" placeholder="Password" required/>
                    </div>
  
                    <div class="text-center pt-1 mb-5 pb-1">
                      <input name="submit" value="Login" class="btn gradient-custom-2 mb-3" type="submit">
                    </div>
  
                  </form>

                  <div class="d-flex align-items-center justify-content-center pb-4">
                      <p class="mb-0 me-2">Don't have an account?</p>
                      <form method="post" class="form-inline">
                        <button name="reg" type="submit" class="btn">Create new</button>
                      </form>
                    </div>
  
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4">Journey SketchPad</h4>
                  <p class="small mb-0">Journey SketchPad is a collaborative and unique application which grants clients the freedom to tell stories in a new way. The application aims to be a choice of medium in art therapy sessions, to enable clients to find their voice through experimentation with digital art.</p>
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
      <footer class="text-muted">
      <div class="container">
        <p class="float-end mb-1">
          <a href="#">Back to top</a>
        </p>
      </div>
    </footer>
</body>
</html>