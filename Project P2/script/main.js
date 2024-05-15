const images = [
        "./images/00001.jpg",
        "./images/00002.jpg",
        "./images/00003.jpg"
   
    ]
    
    
    window.onload = () => {
      
        document.body.style.backgroundImage = `url(${images[0]})`
        document.querySelector('.hidden').src = images[1]
        let i = 1
        setInterval(() => {
          document.body.style.backgroundImage = `url(${images[i++]})`
      
          if (i === images.length) i = 0
          else {
            
            document.querySelector('.hidden').src = images[i]
          }
        }, 7000)
      }
      
      function toggleSize(element) {
        if (element.style.width === "60%") {
            element.style.width = "200px";
        } else {
            element.style.width = "60%";
        }
        element.style.height = "auto";
        element.style.transition = "width 0.5s ease";
    }