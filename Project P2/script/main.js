const images = [
        "./images/gentlemen.jpg",
        "./images/crown.jpg",
        "./images/witcher.jpg"
   
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
      
      
