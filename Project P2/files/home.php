<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once('../helpers/helpers.php');

$email = $_SESSION['email'];
$series = getActiveSeries($db, $email);

$randomSeries = getRandomSeries($db);  
$imageSrc = "../images/"

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/main.js">
        
    </script>
</head>
<body class="home"> 
<nav>
    <div class="bg-img">
    <div class="container">
    <div class="topnav">
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>  
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="./profile.php">Profile</a>
            <a href="./uitlog.php">Logout</a>
        </article></div></div></div>
    </nav>       

    
    <main class="homepage">
       
    
    <section class="active-section">
    <h2>Aanbevolen</h2>
    <div class="container">
        <div class="carousel-view">
            <button id="prev-btn" class="prev-btn">&#129084;</button>
            <div id="item-list" class="item-list">
                <?php foreach($series as $serie) { ?>
                    <a href="./serie.php?id=<?php echo $serie['SerieID']; ?>">
                    <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                    <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                        <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="" ></a>
                <?php } ?>
            </div>
            <button id="next-btn" class="next-btn">&#129086</button>
        </div>
    </div>
</section>

<section class="active-section">
    <h2>Popular</h2>
    <div class="container">
        <div class="carousel-view">
            <button id="prev-btn" class="prev-btn">&#129084;</button>
            <div id="item-list" class="item-list">
                <?php foreach($randomSeries as $serie) { ?>
                    <a href="./serie.php?id=<?php echo $serie['SerieID']; ?>">
                    <img src="../images/0000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                    <img src="../images/000<?php echo $serie['SerieID']; ?>.jpg" alt="" >
                        <img src="../images/00<?php echo $serie['SerieID']; ?>.jpg" alt="" ></a>
                <?php } ?>
            </div>
            <button id="next-btn" class="next-btn">&#129086</button>
        </div>
    </div>
</section>

    </main>
    <footer class="footer">
    <div class="footer__container">
        <div class="footer__info">
            <h3 class="footer__title">Bedrijfsnaam</h3>
            <p>1234 Adresstraat,<br>Stad, Land</p>
            <p>Telefoon: (123) 456-7890</p>
            <p>Email: info@bedrijf.nl</p>
        </div>

        <div class="footer__links">
            <h3 class="footer__title">Snelle Links</h3>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="#">Diensten</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer__social">
            <h3 class="footer__title">Volg ons</h3>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">LinkedIn</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </div>
</footer>
<script>
   const prev = document.getElementById('prev-btn');
const next = document.getElementById('next-btn');
const list = document.getElementById('item-list');
const itemWidth = 150;
const padding = 10;

prev.addEventListener('click', () => {
    list.scrollLeft -= (itemWidth + padding);
});

next.addEventListener('click', () => {
    list.scrollLeft += (itemWidth + padding);
});
</script>
</body>
</html>

