<?php
require('connection.php');
require('auth.php');
    
$frame_id = $_SESSION['frame'];

$dataURL = $_POST['dataURL'];
$data = str_replace('data:image/png;base64,', '', $dataURL);
$data = str_replace(' ', '+', $data);
$stmt = $conn->prepare("UPDATE frames SET frame = ? WHERE frame_id = ?");
$stmt->bind_param("ss", $data, $frame_id);
$stmt->execute();
$stmt->close();
?>