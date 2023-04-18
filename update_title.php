<?php
require('connection.php');
require('auth.php');
// Get the ID and updated content from the XHR request
$id = $_POST["id"];
$updatedTitle = $_POST["title"];

// Save the updated content to a file
$stmt = $conn->prepare("UPDATE storyboards SET title = ? WHERE storyboard_id = ?");
$stmt->bind_param("ss", $updatedTitle, $id);
$stmt->execute();
$stmt->close();
// Send a response back to the XHR request
echo 'Updated content saved successfully.';
?>