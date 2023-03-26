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
require ('auth.php');

//if submitted, update db
if (isset($_POST['submit'])){
  $title = stripslashes($_REQUEST['title']);
  if ($title){
    $title = mysqli_real_escape_string($conn,$title);
  }
  $frames = $_REQUEST['quantity'];

  $storyboard_id = uniqid();
  $client_id = $_SESSION['client'];

    $add_storyboard = "INSERT into `storyboards` (client_id, storyboard_id, no_frames, title)
    VALUES ('$client_id', '$storyboard_id', '$frames', '$title')";

    $result = mysqli_query($conn, $add_storyboard);

    for ($i = 1; $i <= $frames; $i++) {
      $frame_id = uniqid();
      $frame_no = $i;
      $add_frames = "INSERT into `frames` (frame_id, storyboard_id, image_no)
      VALUES ('$frame_id', '$storyboard_id', '$frame_no')";

      $result2 = mysqli_query($conn, $add_frames);
      if ($result2){
        $i++;
      } else {
        echo 'frame failed to add';
      }
    }

  if ($result && $result2){
    $_SESSION['storyboard'] = $storyboard_id;
    header('Location: open_storyboard.php');
    die();
  } else {
    echo "<div class='form'>
    <h3>Could not create storyboard, please try again.</h3>
    </div>";
  }
}
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
  
                  <form name="add_storyboard" action="" method="post">
                    <p>Enter the storyboard information below:</p>
  

                    <div class="form-outline mb-4">
                      <input type="text" name="title" id="form2Example11" class="form-control"
                        placeholder="Optional*"  />
                      <label class="form-label" for="form2Example11">Title or Theme</label>
                    </div>

                    <div class="form-outline mb-4">
                      <label for="frames">Enter number of frames (between 1 and 5):</label>
                      <input type="number" id="quantity" name="quantity" min="1" max="5">
                    </div>

  
                    <div class="text-center pt-1 mb-5 pb-1">
                      <input type="submit" name="submit" value="Add Storyboard" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">
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