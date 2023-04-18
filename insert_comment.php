<?php
require('connection.php');
require('auth.php');
// Get the ID and updated content from the XHR request
$id = $_POST["id"];
$comment = $_POST["comment"];
$comment_id = uniqid();

$stmt = ("INSERT INTO comments (comment_id, comment_text, frame_id) VALUES ('$comment_id', '$comment', '$id')");
$result = mysqli_query($conn, $stmt);

if ($result){
    echo "New record created successfully";
    header('Refresh:0');
} else {
    echo "Unable to create record";
};
?>