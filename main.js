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

let prevX = null;
let prevY = null;
let selectedColor = "#000";
let selectedTool = "brush";
let brushWidth = 5;

let state = [];
let removed = [];
current_state = 0;

let draw = false;

toolBtns.forEach(btn => {
    btn.addEventListener("click", () => { // adding click event to all tool option
        // removing active class from the previous option and adding on current clicked option
        document.querySelector(".active").classList.remove("active")
        btn.classList.add("active")
        selectedTool = btn.id
    })
})


colorPicker.addEventListener("change", () => {
    // passing picked color value from color picker to last color btn background
    colorPicker.parentElement.style.background = colorPicker.value;
    colorPicker.parentElement.click();
});

sizeSlider.addEventListener("change", () => brushWidth = sizeSlider.value)

const stopDraw = () => {
    draw = false

    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
    state.push(snapshot)

    if (state.length > 0){
        current_state = state.length - 1
    }
    console.log("finished draw:"+current_state)

}

let clearBtn = document.querySelector(".clear")
clearBtn.addEventListener("click", () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    stopDraw()
})

let exportBtn = document.querySelector(".export")
exportBtn.addEventListener("click", () => {
    let data = canvas.toDataURL("imag/png")
    let a = document.createElement("a")
    a.href = data
    a.download = "sketch.png"
    a.click()
})

let saveBtn = document.querySelector(".save")
saveBtn.addEventListener("click", () => {  
    dataURL = canvas.toDataURL()

    const xhr = new XMLHttpRequest()
    xhr.open('POST', 'save.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText)
        }
    }

    xhr.send('dataURL='+ encodeURIComponent(dataURL)) 
})

let undoBtn = document.querySelector(".undo")
undoBtn.addEventListener("click", () => {
    if (state.length > 0){
        to_remove = state.pop()
        removed.push(to_remove)
        current_state = (state.length) - 1

        if (current_state < 0){
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            stopDraw()
        } else{
            ctx.putImageData(state[current_state], 0, 0)
        }
    }
})

let redoBtn = document.querySelector(".redo")
redoBtn.addEventListener("click", () => {
    if (removed.length > 0){
        to_add = removed.pop()
        state.push(to_add)

        current_state = state.length - 1
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
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fillRect(e.clientX, e.clientY, prevX - e.clientX, prevY - e.clientY)
        }
        ctx.strokeRect(e.clientX, e.clientY, prevX - e.clientX, prevY - e.clientY)
    }
}

const circMouse = (e) => {
    if (draw) {
        ctx.beginPath()
        let radius = Math.sqrt(Math.pow((prevX - e.clientX), 2) + Math.pow((prevY - e.clientY), 2))
        ctx.arc(prevX, prevY, radius, 0, 2 * Math.PI)
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
        ctx.moveTo(prevX, prevY)
        ctx.lineTo(e.clientX, e.clientY)
        ctx.lineTo(prevX * 2 - e.clientX, e.clientY)
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        ctx.closePath()
        ctx.stroke()
    }
}

const brushMouse = (e) => {
    let mouseX = e.clientX
    let mouseY = e.clientY

    ctx.strokeStyle = selectedColor
    ctx.lineCap = 'round'
    ctx.moveTo(prevX, prevY)
    ctx.lineTo(mouseX, mouseY)
    ctx.stroke()

    prevX = e.clientX
    prevY = e.clientY
}

const rectTouch = (e) => {
    e.preventDefault()
    if (draw){
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fillRect(e.touches[0].clientX, e.touches[0].clientY, prevX - e.touches[0].clientX, prevY - e.touches[0].clientY)
        }
       ctx.strokeRect(e.touches[0].clientX, e.touches[0].clientY, prevX - e.touches[0].clientX, prevY - e.touches[0].clientY)
    }
}

const circTouch = (e) => {
    e.preventDefault()
    if (draw) {
        ctx.beginPath()
        let radius = Math.sqrt(Math.pow((prevX - e.touches[0].clientX), 2) + Math.pow((prevY - e.touches[0].clientY), 2))
        ctx.arc(prevX, prevY, radius, 0, 2 * Math.PI)
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        ctx.stroke()
    }
}

const triTouch = (e) => {
    e.preventDefault()
    if (draw) {
        ctx.beginPath();
        ctx.moveTo(prevX, prevY)
        ctx.lineTo(e.touches[0].clientX, e.touches[0].clientY)
        ctx.lineTo(prevX * 2 - e.touches[0].clientX, e.touches[0].clientY)
        if (fillColor.checked){
            ctx.fillStyle = selectedColor
            ctx.fill()
        }
        ctx.closePath()
        ctx.stroke()
    }
}

const brushTouch = (e) => {
    e.preventDefault()
    let touchX = e.touches[0].clientX
    let touchY = e.touches[0].clientY

    ctx.moveTo(prevX, prevY)
    ctx.lineTo(touchX, touchY)
    ctx.stroke()

    prevX = e.touches[0].clientX
    prevY = e.touches[0].clientY
}

const startDrawMouse = (e) => {
    draw = true
    prevX = e.clientX
    prevY = e.clientY

    ctx.beginPath()
    ctx.strokeStyle = selectedColor
    ctx.lineWidth = brushWidth

    if (state.length === 0){
        snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
    }
}

const drawingMouse = (e) => {
    if (!draw) return
    
    ctx.putImageData(snapshot, 0, 0)
    
    if (selectedTool === "brush"){
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
    draw = true
    prevX = e.touches[0].clientX
    prevY = e.touches[0].clientY

    ctx.beginPath()
    ctx.lineWidth = brushWidth
    ctx.lineCap = 'round'
    ctx.strokeStyle = selectedColor

    if (state.length === 0){
        snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height)
    }
}

const drawingTouch = (e) => {
    e.preventDefault()
    if (!draw) return
    ctx.putImageData(snapshot, 0, 0);

    if (selectedTool === "brush"){
        brushTouch(e)
    } else if (selectedTool === "rectangle"){
        rectTouch(e)
    } else if (selectedTool === "circle"){
        circTouch(e)
    } else if (selectedTool === "triangle"){
        triTouch(e)
    }
}


colorPicker.addEventListener("change", () => {
    // passing picked color value from color picker to last color btn background
    colorPicker.parentElement.style.background = colorPicker.value;
    colorPicker.parentElement.click();
});

canvas.addEventListener("mousedown", startDrawMouse)
canvas.addEventListener("mouseup", stopDraw)
canvas.addEventListener("mousemove", drawingMouse)

canvas.addEventListener("touchstart", startDrawTouch, {passive:false})
canvas.addEventListener("touchend", stopDraw, {passive:false})
canvas.addEventListener("touchmove", drawingTouch, {passive:false})