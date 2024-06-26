<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once('../helpers/helpers.php');

$imageSrc = "../images/";
$user = new User();
$email = $_SESSION['email'];
$profileData = $user->getProfile($email);
$serie = new Serie();
$stream = new Stream();
$aanbevolen = $stream->aanbevolen($profileData[0]['KlantNr']);
$email = $_SESSION['email'];
$series = $serie->getActiveSeries($email);

$randomSeries = $serie->getRandomSeries();
$imageSrc = "../images/";

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
    
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>  
            <a href="./home.php">Home</a>
            <a href="search.php">Search</a>
            <a href="#">Contact</a>
            <a href="./profile.php">Profile</a>
            <a href="historie.php">History</a>
            <a href="./uitlog.php">Logout</a>
        </article>
    </nav>       

    
    <main class="homepage">
   
    <section class="frontserie">
        <article class="witcher">
            <div class="content">
                <h1>The Witcher</h1>
                <p>In de mystieke wereld van The Witcher liggen monsters op de loer, en Geralt van Rivia, een eenzame en legendarische monsterjager, vecht met ongekende kracht tegen het kwaad. Terwijl hij door gevaarlijke landschappen reist, ontdekt hij dat de grens tussen goed en kwaad vaak vervaagt. Beleef de magie, actie en intriges in dit epische avontuur waar niets is wat het lijkt.</p>
                <button id="showVideoBtn">Show Trailer</button>
            </div>
            <div class="img-container">
                <img src="../images/witcher.jpg" alt="Witcher">
                <div id="videoContainer" class="video-container">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/kr3br-3i8TY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </article>
    </section>
       
        <section class="active-section">
    <h2>Kijk verder</h2>
    <div class="container">
        <div class="carousel-view">
            <button id="prev-btn" class="prev-btn">&#129084;</button>
            <div id="item-list" class="item-list">
               
                <?php foreach($aanbevolen as $serie) { ?>
                <a href="serie.php?id=<?php echo $serie['SerieID'];?>">
                <img src="<?php
                    $serieIDLength = strlen($serie['SerieID']);
                switch ($serieIDLength) {
                    case 1:
                        echo $imageSrc . "0000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 2:
                        echo $imageSrc . "000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 3:
                        echo $imageSrc . "00" . $serie['SerieID'] . ".jpg";
                        break;
                    case 4:
                        echo $imageSrc . "0" . $serie['SerieID'] . ".jpg";
                        break;
                    default:
                        echo $imageSrc . $serie['SerieID'] . ".jpg";
                        break;
                }
            ?>" alt="Series Image"></a>
                <?php } ?>
            </div>
            <button id="next-btn" class="next-btn">&#129086</button>
        </div>
    </div>
</section>
    <section class="active-section">
    <h2>Aanbevolen</h2>
    <div class="container">
        <div class="carousel-view">
            <button id="prev-btn" class="prev-btn">&#129084;</button>
            <div id="item-list" class="item-list">
               
                <?php foreach($series as $serie) { ?>
                <a href="serie.php?id=<?php echo $serie['SerieID'];?>">
                <img src="<?php
                    $serieIDLength = strlen($serie['SerieID']);
                switch ($serieIDLength) {
                    case 1:
                        echo $imageSrc . "0000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 2:
                        echo $imageSrc . "000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 3:
                        echo $imageSrc . "00" . $serie['SerieID'] . ".jpg";
                        break;
                    case 4:
                        echo $imageSrc . "0" . $serie['SerieID'] . ".jpg";
                        break;
                    default:
                        echo $imageSrc . $serie['SerieID'] . ".jpg";
                        break;
                }
            ?>" alt="Series Image"></a>
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
                <a href="serie.php?id=<?php echo $serie['SerieID'];?>">
                <img src="<?php
                    $serieIDLength = strlen($serie['SerieID']);
                switch ($serieIDLength) {
                    case 1:
                        echo $imageSrc . "0000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 2:
                        echo $imageSrc . "000" . $serie['SerieID'] . ".jpg";
                        break;
                    case 3:
                        echo $imageSrc . "00" . $serie['SerieID'] . ".jpg";
                        break;
                    case 4:
                        echo $imageSrc . "0" . $serie['SerieID'] . ".jpg";
                        break;
                    default:
                        echo $imageSrc . $serie['SerieID'] . ".jpg";
                        break;
                }
            ?>" alt="Series Image"></a>
                <?php } ?>
            </div>
            <button id="next-btn" class="next-btn">&#129086;</button>
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
        document.getElementById('showVideoBtn').addEventListener('click', function() {
            var videoContainer = document.getElementById('videoContainer');
            videoContainer.classList.toggle('show');
        });


        const prev = document.getElementById('prev-btn');
        const next = document.getElementById('next-btn');
        const list = document.getElementById('item-list');
        const itemWidth = 150;
        const padding = 10;

        function scrollList(direction) {
            if (direction === 'prev') {
                list.scrollLeft -= (itemWidth + padding);
            } else if (direction === 'next') {
                list.scrollLeft += (itemWidth + padding);
            }
        }

        prev.addEventListener('click', () => scrollList('prev'));
        next.addEventListener('click', () => scrollList('next'));
    </script>
</body>
</html>

