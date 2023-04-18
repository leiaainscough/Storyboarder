function saveComment(frame_id) {
  console.log("clicked")
  var comment = document.getElementById("comment").value
  var id = frame_id
  var xhr = new XMLHttpRequest()
  xhr.open("POST", "insert_comment.php", true)
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
  xhr.send("id=" + id + "&comment=" + comment)


  xhr.onreadystatechange = function() {
    if (xhr.readyState == XMLHttpRequest.DONE) {
        location.reload();
    }
}
}