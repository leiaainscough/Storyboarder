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
<?php
require ('connection.php');

//if submitted, update db
if (isset($_REQUEST['username'])){
  $username = stripslashes($_REQUEST['username']);
  $username = mysqli_real_escape_string($conn,$username);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn,$password);

  if (isset($_POST['user-type'])){
    $user_type = "T";
  } else {
    $user_type = "C";
  }

  $user_type = mysqli_real_escape_string($conn,$user_type);

  $client_id = uniqid();

  
    $query = "INSERT into `users` (username, password, user_type, client_id)
    VALUES ('$username', '$password', '$client_id', '$user_type')";

  $result = mysqli_query($conn, $query);
  if ($result){
    echo "<div class='form'>
    <h3>You are registered successfully.</h3>
    <br/>Click here to <a href='login.php'>Login</a></div>";
  }

  }else{
?>

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
  
                  <form name="registration" action="" method="post">
                    <p>Enter the form below:</p>
  
                    <div class="form-outline mb-4">
                      <input type="text" name="username" id="form2Example11" class="form-control"
                        placeholder="Username" required />
                      <label class="form-label" for="form2Example11">Username</label>
                    </div>
  
                    <div class="form-outline mb-4">
                      <input type="password" name="password" id="form2Example22" class="form-control" required/>
                      <label class="form-label" for="form2Example22">Password</label>
                    </div>

                      
                    <div class="form-outline mb-4 dropdown">
                      <input type="checkbox" name="user-type" class="form-check-input" id="dropdownCheck">
                      <label class="form-label" for="form2Example22">Tick if you are a Therapist:</label>
                    </div>
  
                    <div class="text-center pt-1 mb-5 pb-1">
                      <input type="submit" name="submit" value="Register" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">
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
  <?php } ?>
</body>
</html>