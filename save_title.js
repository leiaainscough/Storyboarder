function saveTitle(storyboard_id) {
  //remove any soaced from id
  storyboard_id = storyboard_id.replace(/\s+/g, '')
  //get the title element from HTML
  var element = document.getElementById("title")
  //get the editted text from the box
  var title = element.innerHTML
  //create server request
  var xhr = new XMLHttpRequest()
  xhr.open("POST", "update_title.php", true)
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
  //send packet with storyboard id and new title
  xhr.send("id=" + storyboard_id + "&title=" + title)

  xhr.onreadystatechange = function(){
    if (xhr.readyState === 4 && xhr.status === 200) {
      window.location.reload()
      //if success, show message to user
      alert("Title saved")
    }
  }
}