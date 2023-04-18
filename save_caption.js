function saveCaption(id) {
  id = id.replace(/\s+/g, '')
  console.log(id)
  var element = document.getElementById(id)
  var caption = element.innerHTML
  console.log(caption)
  var xhr = new XMLHttpRequest()
  xhr.open("POST", "update_caption.php", true)
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")

  xhr.send("id=" + id + "&caption=" + caption)

  xhr.onreadystatechange = function(){
    if (xhr.readyState === 4 && xhr.status === 200) {
      window.location.reload()
      alert("Caption saved")
    }
  }
}