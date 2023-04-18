function saveTitle(storyboard_id) {
  console.log("clicked")
  storyboard_id = storyboard_id.replace(/\s+/g, '')
  var element = document.getElementById("title")
  var title = element.innerHTML
  var xhr = new XMLHttpRequest()
  xhr.open("POST", "update_title.php", true)
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
  xhr.send("id=" + storyboard_id + "&title=" + title)

  xhr.onreadystatechange = function(){
    if (xhr.readyState === 4 && xhr.status === 200) {
      window.location.reload()
      alert("Title saved")
    }
  }
}