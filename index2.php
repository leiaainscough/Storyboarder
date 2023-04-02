<?php
    require('connection.php');
    require('auth.php');
    
    function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';

    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
    }

    $frame_id = $_SESSION['frame'];

    $get_frame = "SELECT frame FROM `frames` WHERE frame_id='$frame_id'";
    $result = mysqli_query($conn, $get_frame);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $frame = $row['frame'];
        };
    } else {
        console_log("frame empty");
    };

    if (isset($_POST['logout'])){
        session_destroy();
        header('Location: login.php');
        die();
    };

    if (isset($_POST['back'])){
        header('Location: open_storyboard.php');
        die();
    };
   
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <title>Document</title>
</head>
<body>
    <canvas id="canvas"></canvas>
    <div class="nav">
        <button class="tool" id="point"><img src="icons/cursor.svg" alt="Select Tool"></button>
        <div class="colors options">
            <div class="option selected">
                <input type="color" id="color-picker" value="#4A98F7">
            </div>
        </div>
        <button class="tool active" id="brush"><img src="icons/brush.svg" alt="PaintBrush"></button>
        <input type="range" id="size-slider" min="1" max="30" value="5">
        <button class="tool" id="eraser"><img src="icons/eraser.svg" alt="Eraser"></button>
        <input class="tool" type="checkbox" id="fill-color">
        <label for="fill"><img src="icons/fill.svg" alt="Fill Tool"></label>
        <button class="tool" id="rectangle"><img src="icons/rectangle.svg" alt="Rectangle"></button>
        <button class="tool" id="circle"><img src="icons/circle.svg" alt="Circle"></button>
        <button class="tool" id="triangle"><img src="icons/triangle.svg" alt="Triangle"></button>
        <button class="undo"><img src="icons/undo.svg" alt="Undo Tool"></button>
        <button class="redo"><img src="icons/redo.svg" alt="Redo Tool"></button>
        <button class="clear">Clear</button>
        <button class="save"><img src="icons/save.svg" alt="Save"></button>
        <button class="export"><img src="icons/download.svg" alt="Download"></button>
        <form method="post" action="" class="form-inline my-2 my-lg-0">
            <button name="back" type="submit"  class="btn btn-sm btn-outline-secondary">Back to Storyboard</button>
        </form>

    </div>

    <script src="main.js"></script>
    <script>
        const setCanvasBackground = () => {
            <?php
                if ($frame){
                    echo'  
                    var img = new Image();
                    img.onload = function() {
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    }
                    img.src = "data:image/png;base64,', $frame,'";';
                            
                } else {
                    console_log("no frame exists");

                    echo'
                    ctx.fillStyle = "#fff";
                    ctx.fillRect(0, 0, canvas.width, canvas.height)
                    ctx.fillStyle = selectedColor';
                }
            ?>
        };

        window.addEventListener("load", () => {
            // setting canvas width/height.. offsetwidth/height returns viewable width/height of an element
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            setCanvasBackground();
        });
        </script>
</body>
</html>