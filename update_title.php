
<?php
require('connection.php');
require('auth.php');
// Get the storyboard id and title from the request
$id = $_POST["id"];
$updatedTitle = $_POST["title"];

// save the title to the storyboards table where the id 
$stmt = $conn->prepare("UPDATE storyboards SET title = ? WHERE storyboard_id = ?");
$stmt->bind_param("ss", $updatedTitle, $id);
$stmt->execute();
$stmt->close();
//send success message
echo 'Updated content saved successfully.';

?>

