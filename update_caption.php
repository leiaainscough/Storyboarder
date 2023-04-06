<?php
require('connection.php');
require('auth.php');
// Get the ID and updated content from the XHR request
$id = $_POST["id"];
$updatedCaption = $_POST["caption"];

// Save the updated content to a file
$stmt = $conn->prepare("UPDATE frames SET caption = ? WHERE frame_id = ?");
$stmt->bind_param("ss", $updatedCaption, $id);
$stmt->execute();
$stmt->close();
// Send a response back to the XHR request
echo 'Updated content saved successfully.';
?>