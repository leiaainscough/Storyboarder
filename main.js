//selecting HTML elements
const canvas = document.querySelector("canvas"),
toolBtns = document.querySelectorAll(".tool"),
sizeSlider = document.querySelector("#size-slider"),
color = document.querySelectorAll(".clr"),
colorBtns = document.querySelectorAll(".colors .option"),
colorPicker = document.querySelector("#color-picker"),
fillColor = document.querySelector("#fill-color");
canvas.height = window.innerHeight;
canvas.width = window.innerWidth;
let ctx = canvas.getContext("2d");

//setting to coordinates to null
let prevX = null;
let prevY = null;
//setting default colour to black
let selectedColor = "#000";
//setting default tool to brush
let selectedTool = "brush";
//setting default line width to 5
let brushWidth = 5;

//creating array for to hold all actions taken by user
let state = [];
//creating array to hold any undone actions
let removed = [];
//setting current state to index 0 in the array
current_state = 0;

//setting draw to false
let draw = false;


//if tool button is clicked, set the button to active and set as selected tool
toolBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelector(".active").classList.remove("active")
        btn.classList.add("active")
        selectedTool = btn.id
    })
})


colorPicker.addEventListener("change", () => {
    //set button background to the colour picked by the user
    colorPicker.parentElement.style.background = colorPicker.value;
    //set the colour to the user selected colour
    selectedColor = colorPicker.value;
    colorPicker.parentElement.click();
});


//change the brush width if size slider is changed
sizeSlider.addEventListener("change", () => brushWidth = sizeSlider.value)

//when mouse or touch is lifted
const stopDraw = () => {
    //stop drawing
    draw = false

    //take snapshot of the current state of the canvas and add to array
    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
    state.push(snapshot)

    //set current state to the last element in the array
    if (state.length > 0){
        current_state = state.length - 1
    }
}

let clearBtn = document.querySelector(".clear")
clearBtn.addEventListener("click", () => {
    //clear the screen by drawing a white rectangle over
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    ctx.fillStyle = "#fff"
    ctx.fillRect(0, 0, canvas.width, canvas.height)    
    stopDraw()
})

let exportBtn = document.querySelector(".export")
exportBtn.addEventListener("click", () => {
    //convert canvas to image
    let data = canvas.toDataURL("imag/png")
    let a = document.createElement("a")
    a.href = data
    a.download = "sketch.png"
    //download image
    a.click()
})

let saveBtn = document.querySelector(".save")
saveBtn.addEventListener("click", () => {  
    //convert canvas to dataURL
    dataURL = canvas.toDataURL()

    //create new server request
    const xhr = new XMLHttpRequest()
    //send post to save php file
    xhr.open('POST', 'save.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200) {
            //if success log the response message
            console.log(xhr.responseText)
        }
    }
    //send image to server for upload to database
    xhr.send('dataURL='+ encodeURIComponent(dataURL)) 
})

let undoBtn = document.querySelector(".undo")
undoBtn.addEventListener("click", () => {
    //if user has drawn anything
    if (state.length > 0){
        //remove most recent action from the array
        to_remove = state.pop()
        //add to the array holding undone actions
        removed.push(to_remove)
        current_state = (state.length) - 1

        if (current_state < 0){
            //if undone the first action, clear the canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            stopDraw()
        } else{
            //draw previous action back on the canvas
            ctx.putImageData(state[current_state], 0, 0)
        }
    }
})

let redoBtn = document.querySelector(".redo")
redoBtn.addEventListener("click", () => {
    if (removed.length > 0){
        //add last element in the array back into state
        to_add = removed.pop()
        state.push(to_add)

        current_state = state.length - 1
        //draw on the canvas
        ctx.putImageData(state[current_state], 0, 0)
    }
})

fileInput.addEventListener('change', () => {  
    const file = fileInput.files[0];
    const reader = new FileReader();
    
    reader.addEventListener('load', () => {
      const img = new Image();
      img.src = reader.result;
      
      img.addEventListener('load', () => {
        const canvasAspectRatio = canvas.width / canvas.height
        const imgAspectRatio = img.width / img.height
        
        let width, height, x, y
        
        if (imgAspectRatio > canvasAspectRatio) {
          // Image is wider than canvas
          width = canvas.width
          height = width / imgAspectRatio
          x = 0
          y = (canvas.height - height) / 2
        } else {
          // Image is taller than canvas
          height = canvas.height
          width = height * imgAspectRatio
          y = 0
          x = (canvas.width - width) / 2
        }
        
        ctx.drawImage(img, x, y, width, height)
        stopDraw()        
      })
    })
    
    reader.readAsDataURL(file)
  })
  
const rectMouse = (e) => {
    if (draw){
        //if user has chosen to fill, draw rectangle and fill using selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fillRect(e.clientX, e.clientY, prevX - e.clientX, prevY - e.clientY)
        }
        //else draw rectangle without the fill
        ctx.strokeRect(e.clientX, e.clientY, prevX - e.clientX, prevY - e.clientY)
    }
}

const circMouse = (e) => {
    if (draw) {
        ctx.beginPath()
        // calculating the radius of the circle using the previous and current coordinates
        let radius = Math.sqrt(Math.pow((prevX - e.clientX), 2) + Math.pow((prevY - e.clientY), 2))
        //draw the circle
        ctx.arc(prevX, prevY, radius, 0, 2 * Math.PI)
        //if fill is checked, fill with selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        ctx.stroke()
    }
}

const triMouse = (e) => {
    if (draw) {
        ctx.beginPath()
        //draw one line from the previous coordinates to the current
        ctx.moveTo(prevX, prevY)
        ctx.lineTo(e.clientX, e.clientY)
        //draw another line from the same point but symmetrical
        ctx.lineTo(prevX * 2 - e.clientX, e.clientY)
        //if fill is checked, fill with selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        //connect the two lines
        ctx.closePath()
        ctx.stroke()
    }
}

const brushMouse = (e) => {
    //get current mouse coordinates
    let mouseX = e.clientX
    let mouseY = e.clientY

    if (selectedTool === "eraser"){
        // if eraser tool is selected, set the brush colour to white
        ctx.strokeStyle = "#fff"
    } else {
        //if the brush tool is selected, use the user selected colour
        ctx.strokeStyle = selectedColor
    }
    ctx.lineCap = 'round'
    //move line from previous mouse placement to current
    ctx.moveTo(prevX, prevY)
    ctx.lineTo(mouseX, mouseY)
    ctx.stroke()

    //set the current mouse coordinates to previous
    prevX = e.clientX
    prevY = e.clientY
}

const rectTouch = (e) => {
    e.preventDefault()
    if (draw){
        //if user has chosen to fill, draw rectangle and fill using selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fillRect(e.touches[0].clientX, e.touches[0].clientY,
                 prevX - e.touches[0].clientX, prevY - e.touches[0].clientY)
        }
        //else draw without fill
       ctx.strokeRect(e.touches[0].clientX, e.touches[0].clientY,
         prevX - e.touches[0].clientX, prevY - e.touches[0].clientY)
    }
}

const circTouch = (e) => {
    e.preventDefault()
    if (draw) {
        ctx.beginPath()
        //calculate radius using previous and current coordinates
        let radius = Math.sqrt(Math.pow((prevX - e.touches[0].clientX), 2) 
            + Math.pow((prevY - e.touches[0].clientY), 2))
        //create circle
        ctx.arc(prevX, prevY, radius, 0, 2 * Math.PI)
        //if fill selected, fill with user selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        //draw
        ctx.stroke()
    }
}

const triTouch = (e) => {
    e.preventDefault()
    if (draw) {
        ctx.beginPath()
        //draw line from previous mouse position to current, and symmetrical
        ctx.moveTo(prevX, prevY)
        ctx.lineTo(e.touches[0].clientX, e.touches[0].clientY)
        ctx.lineTo(prevX * 2 - e.touches[0].clientX, e.touches[0].clientY)
        //if fill, fill with user selected colour
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        //connect the two lines
        ctx.closePath()
        //draw
        ctx.stroke()
    }
}

const brushTouch = (e) => {
    e.preventDefault()
    //get current position
    let touchX = e.touches[0].clientX
    let touchY = e.touches[0].clientY
    
    if (selectedTool === "eraser"){
        //if eraser is chosen, set brush colour to white
        ctx.strokeStyle = "#fff"
    } else {
        //if brush is chosen, set colour to the user selected colour
        ctx.strokeStyle = selectedColor
    }

    //draw line from precious position to current
    ctx.moveTo(prevX, prevY)
    ctx.lineTo(touchX, touchY)
    ctx.stroke()

    //set the previous position to the current
    prevX = e.touches[0].clientX
    prevY = e.touches[0].clientY
}

const startDrawMouse = (e) => {
    //start drawing
    draw = true
    //set previous coordinates to the current position
    prevX = e.clientX
    prevY = e.clientY

    //start path with chosen colour and line weight
    ctx.beginPath()
    ctx.strokeStyle = selectedColor
    ctx.lineWidth = brushWidth

    //take a snapshot of the current screen
    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
}

const drawingMouse = (e) => {
    if (!draw) return
    
    //draw snapshot
    ctx.putImageData(snapshot, 0, 0)
    
    if (selectedTool === "brush" || selectedTool === "eraser"){
        brushMouse(e)
    } else if (selectedTool === "rectangle"){
        rectMouse(e)
    } else if (selectedTool === "circle"){
        circMouse(e)
    } else if (selectedTool === "triangle"){
        triMouse(e)
    }
}

const startDrawTouch = (e) => {
    e.preventDefault()
    //start drawing
    draw = true
    //set previous coordinates to the current position
    prevX = e.touches[0].clientX
    prevY = e.touches[0].clientY

    //start path with chosen colour and line weight
    ctx.beginPath()
    ctx.lineWidth = brushWidth
    ctx.lineCap = 'round'
    ctx.strokeStyle = selectedColor

    //take a snapshot of the current screen
    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
}

const drawingTouch = (e) => {
    e.preventDefault()
    if (!draw) return

    //draw snapshot
    ctx.putImageData(snapshot, 0, 0)

    if (selectedTool === "brush" || selectedTool === "eraser"){
        brushTouch(e)
    } else if (selectedTool === "rectangle"){
        rectTouch(e)
    } else if (selectedTool === "circle"){
        circTouch(e)
    } else if (selectedTool === "triangle"){
        triTouch(e)
    }
}

//mouse events
canvas.addEventListener("mousedown", startDrawMouse)
canvas.addEventListener("mouseup", stopDraw)
canvas.addEventListener("mousemove", drawingMouse)

//touchscreen events
canvas.addEventListener("touchstart", startDrawTouch, {passive:false})
canvas.addEventListener("touchend", stopDraw, {passive:false})
canvas.addEventListener("touchmove", drawingTouch, {passive:false})