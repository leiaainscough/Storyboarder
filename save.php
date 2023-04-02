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

$dataURL = $_POST['dataURL'];
$data = str_replace('data:image/png;base64,', '', $dataURL);
$data = str_replace(' ', '+', $data);
$stmt = $conn->prepare("UPDATE frames SET frame = ? WHERE frame_id = ?");
$stmt->bind_param("ss", $data, $frame_id);
$stmt->execute();
$stmt->close();
?>