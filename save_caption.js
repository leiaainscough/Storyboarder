function saveContent(image_no) {
  console.log("clicked")
  image_no = image_no.replace(/\s+/g, '')
  var element = document.getElementById(image_no)
  var caption = element.innerHTML
  var id = element.getAttribute("data-id")
  var xhr = new XMLHttpRequest()
  xhr.open("POST", "update_caption.php", true)
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
  xhr.send("id=" + id + "&caption=" + caption)

  console.log(xhr.responseText)
}